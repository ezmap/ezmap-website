<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class MapController extends Controller
{

  /*
   * Users should be logged in for all endpoints on this map
   */
  public function __construct()
  {
    $this->middleware(['auth', 'pjax'])->except(['show']);
  }

  /**
   * Display a listing of the map.
   *
   * @return mixed
   */
  public function index(Request $request)
  {
    $maps        = $request->user()->maps;
    $deletedMaps = Map::onlyTrashed()
        ->where('user_id', $request->user()->id)
        ->get();

    return view('map.index', compact('maps', 'deletedMaps'));
  }

  /**
   * Show the form for creating a new map.
   *
   * @return mixed
   */
  public function create()
  {
    return view('index');
  }

  /**
   * Store a newly created map in storage.
   *
   * @param Request $request
   * @return mixed
   */
  public function store(Request $request)
  {
    $map                = new Map($request->all());
    $map->user_id       = $request->user()->id;
    $map->responsiveMap = $request->has('responsiveMap');

    $options           = (array)$map->mapOptions;
    $options           = $this->cleanMapOptions($request, $options);
    $map->mapOptions   = (object)$options;
    $map->heatmapLayer = $this->cleanHeatmapLayer($request, (array)$map->heatmapLayer);

    $map->save();

    return redirect()->route('map.edit', $map);
  }

  /**
   * Display the specified map.
   *
   * @param int $id
   * @return mixed
   */
  public function show(Map $map)
  {
    return $map->embeddable
        ? response()
            ->view('map.show', compact('map'), 200)
            ->header('Content-Type', 'text/javascript; charset=UTF-8')
        : abort(403);
  }

  /**
   * Show the form for editing the specified map.
   *
   * @param int $id
   * @return mixed
   */
  public function edit(Request $request, Map $map)
  {
    $this->authorize($map);
    $sorts   = ['name', 'description', 'author', 'snazzy_id', 'id'];
    $sort    = 'name';
    $order   = 'asc';
    $appends = [];
    if ($request->has('sort') || $request->has('order'))
    {
      if ($request->has('sort'))
      {
        $sort            = in_array($request->input('sort'), $sorts) ? $request->input('sort') : 'name';
        $appends['sort'] = $sort;
      }
      if ($request->has('order'))
      {
        $order            = ($request->input('order') == 'true') ? 'desc' : 'asc';
        $appends['order'] = ($request->input('order') == 'desc') ? 'true' : 'false';
      }
    }

    return view('index', compact('map', 'sort', 'order'));
  }

  /**
   * Update the specified map in storage.
   *
   * @param Request $request
   * @param int     $id
   * @return mixed
   */
  public function update(Request $request, Map $map)
  {
    $this->authorize($map);

    $map->title         = $request->has('title') ? $request->input('title') : '';
    $map->apiKey        = $request->has('apiKey') ? $request->input('apiKey') : '';
    $map->mapContainer  = $request->has('mapContainer') ? $request->input('mapContainer') : 'ez-map';
    $map->width         = $request->has('width') ? $request->input('width') : $map->width;
    $map->height        = $request->has('height') ? $request->input('height') : $map->height;
    $map->latitude      = $request->has('latitude') ? $request->input('latitude') : $map->latitude;
    $map->longitude     = $request->has('longitude') ? $request->input('longitude') : $map->longitude;
    $map->markers       = $request->has('markers') ? $request->input('markers') : $map->markers;
    $map->heatmap       = $request->has('heatmap') ? $request->input('heatmap') : $map->heatmap;
    $map->heatmapLayer  = $request->has('heatmapLayer') ? $request->input('heatmapLayer') : $map->heatmapLayer;
    $map->mapOptions    = $request->has('mapOptions') ? $request->input('mapOptions') : $map->mapOptions;
    $map->responsiveMap = $request->has('responsiveMap');
    $map->embeddable    = $request->has('embeddable');
    $map->theme_id      = $request->has('theme_id') ? $request->theme_id : $map->theme_id;

    $options         = (array)$map->mapOptions;
    $options         = $this->cleanMapOptions($request, $options);
    $map->mapOptions = (object)$options;

    $map->heatmapLayer = $this->cleanHeatmapLayer($request, (array)$map->heatmapLayer);

    $map->save();

    return redirect()->route('map.edit', $map);
  }

  /**
   * Remove the specified map from storage.
   *
   * @param int $id
   * @return mixed
   */
  public function destroy(Map $map)
  {
    $this->authorize($map);
    $map->delete();

    return redirect()->route('home');
  }

  public function undelete(Request $request, $id)
  {
    Map::onlyTrashed()
        ->where('id', $id)
        ->where('user_id', $request->user()->id)
        ->restore();

    return redirect()->route('home');
  }

  /**
   * @param Request $request
   * @param         $options
   * @return array
   */
  protected function cleanMapOptions(Request $request, array $options)
  {
    // possibly don't need to do this as we go through each option afterwards anyway. I guess this'll pick up stragglers!
    $options = collect($options)->transform(function ($item) {
      return ($item == 'on') ? 'true' : $item;
    })->all();

    $options['doubleClickZoom']       = $request->has("mapOptions.doubleClickZoom") ? 'true' : 'false';
    $options['clickableIcons']        = $request->has("mapOptions.clickableIcons") ? 'true' : 'false';
    $options['draggable']             = $request->has("mapOptions.draggable") ? 'true' : 'false';
    $options['showFullScreenControl'] = $request->has("mapOptions.showFullScreenControl") ? 'true' : 'false';
    $options['keyboardShortcuts']     = $request->has("mapOptions.keyboardShortcuts") ? 'true' : 'false';
    $options['showMapTypeControl']    = $request->has("mapOptions.showMapTypeControl") ? 'true' : 'false';
    $options['showScaleControl']      = $request->has("mapOptions.showScaleControl") ? 'true' : 'false';
    $options['scrollWheel']           = $request->has("mapOptions.scrollWheel") ? 'true' : 'false';
    $options['showStreetViewControl'] = $request->has("mapOptions.showStreetViewControl") ? 'true' : 'false';
    $options['showZoomControl']       = $request->has("mapOptions.showZoomControl") ? 'true' : 'false';

    return $options;
  }

  protected function cleanHeatmapLayer(Request $request, $heatmapLayer)
  {
    $heatmapLayer['dissipating'] = false;

    $heatmapLayer            = collect($heatmapLayer)->transform(function ($item) {
      return ($item === 'on') ? true : $item;
    })->all();
    $heatmapLayer['radius']  = intval($heatmapLayer['radius'] ?? 0);
    $heatmapLayer['opacity'] = floatval($heatmapLayer['opacity'] ?? 0);

    return json_encode($heatmapLayer);
  }

  public function image(Request $request, Map $map)
  {
    $this->authorize($map);

    $extension = $request->get('type') ?? "png";

    return view('map.image', compact('map', 'extension'));
  }

  public function download(Request $request, Map $map)
  {
    $this->authorize($map);

    $extension = 'png';

    $filename  = Str::slug($map->title) . ".{$extension}";
    $tempImage = tempnam(sys_get_temp_dir(), $filename);
    copy($map->getImage($extension), $tempImage);

    return response()
        ->download($tempImage, $filename)
        ->deleteFileAfterSend(true);
  }

  public function exportKml(Request $request, Map $map)
  {
    $this->authorize($map);

    $kmlContent = $this->generateKml($map);
    $filename = Str::slug($map->title) . '.kml';

    return response($kmlContent)
        ->header('Content-Type', 'application/vnd.google-earth.kml+xml')
        ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
  }

  public function exportKmz(Request $request, Map $map)
  {
    $this->authorize($map);

    $kmlContent = $this->generateKml($map);
    $filename = Str::slug($map->title) . '.kmz';
    
    // Create a temporary ZIP file
    $tempKmz = tempnam(sys_get_temp_dir(), $filename);
    $zip = new \ZipArchive();
    
    if ($zip->open($tempKmz, \ZipArchive::CREATE) === TRUE) {
      $zip->addFromString('doc.kml', $kmlContent);
      $zip->close();
      
      return response()
          ->download($tempKmz, $filename)
          ->deleteFileAfterSend(true);
    } else {
      return response()->json(['error' => 'Failed to create KMZ file'], 500);
    }
  }

  private function generateKml(Map $map)
  {
    $kml = new \DOMDocument('1.0', 'UTF-8');
    $kml->formatOutput = true;

    // Create root kml element
    $kmlElement = $kml->createElement('kml');
    $kmlElement->setAttribute('xmlns', 'http://www.opengis.net/kml/2.2');
    $kml->appendChild($kmlElement);

    // Create document element
    $document = $kml->createElement('Document');
    $kmlElement->appendChild($document);

    // Add document name
    $name = $kml->createElement('name', htmlspecialchars($map->title ?: 'EZ Map'));
    $document->appendChild($name);

    // Add description
    $description = $kml->createElement('description', 'Map created with EZ Map (ezmap.co)');
    $document->appendChild($description);

    // Add markers as placemarks
    if ($map->markers && count($map->markers) > 0) {
      foreach ($map->markers as $index => $marker) {
        // Validate marker data
        if (!isset($marker->lat) || !isset($marker->lng)) {
          continue;
        }

        $placemark = $kml->createElement('Placemark');
        $document->appendChild($placemark);

        // Marker name/title
        $placemarkName = $kml->createElement('name', htmlspecialchars($marker->title ?: "Marker " . ($index + 1)));
        $placemark->appendChild($placemarkName);

        // Marker description (from info window)
        if (isset($marker->infoWindow) && !empty($marker->infoWindow->content)) {
          $placemarkDesc = $kml->createElement('description');
          $placemarkDesc->appendChild($kml->createCDATASection($marker->infoWindow->content));
          $placemark->appendChild($placemarkDesc);
        }

        // Point coordinates
        $point = $kml->createElement('Point');
        $placemark->appendChild($point);

        $coordinates = $kml->createElement('coordinates', $marker->lng . ',' . $marker->lat . ',0');
        $point->appendChild($coordinates);

        // Style for custom icons
        if (!empty($marker->icon)) {
          $styleId = 'icon-style-' . $index;
          
          // Create style
          $style = $kml->createElement('Style');
          $style->setAttribute('id', $styleId);
          $document->appendChild($style);

          $iconStyle = $kml->createElement('IconStyle');
          $style->appendChild($iconStyle);

          $icon = $kml->createElement('Icon');
          $iconStyle->appendChild($icon);

          $href = $kml->createElement('href', htmlspecialchars($marker->icon));
          $icon->appendChild($href);

          // Reference style in placemark
          $styleUrl = $kml->createElement('styleUrl', '#' . $styleId);
          $placemark->appendChild($styleUrl);
        }
      }
    }

    // Add heatmap data as points if available
    if ($map->heatmap && count($map->heatmap) > 0) {
      $folder = $kml->createElement('Folder');
      $document->appendChild($folder);

      $folderName = $kml->createElement('name', 'Heatmap Data');
      $folder->appendChild($folderName);

      foreach ($map->heatmap as $index => $hotspot) {
        // Validate heatmap data
        if (!isset($hotspot->weightedLocation->location->lat) || !isset($hotspot->weightedLocation->location->lng)) {
          continue;
        }

        $placemark = $kml->createElement('Placemark');
        $folder->appendChild($placemark);

        $placemarkName = $kml->createElement('name', 'Heatmap Point ' . ($index + 1));
        $placemark->appendChild($placemarkName);

        $placemarkDesc = $kml->createElement('description', 'Weight: ' . ($hotspot->weightedLocation->weight ?? 1));
        $placemark->appendChild($placemarkDesc);

        $point = $kml->createElement('Point');
        $placemark->appendChild($point);

        $coordinates = $kml->createElement('coordinates', 
          $hotspot->weightedLocation->location->lng . ',' . 
          $hotspot->weightedLocation->location->lat . ',0'
        );
        $point->appendChild($coordinates);
      }
    }

    return $kml->saveXML();
  }


}
