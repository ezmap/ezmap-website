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


}
