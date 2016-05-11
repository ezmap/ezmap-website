<?php

namespace App\Http\Controllers;

use App\Map;
use App\Theme;
use Illuminate\Http\Request;

use App\Http\Requests;

class MapController extends Controller
{

    /*
     * Users should be logged in for all endpoints on this map
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the map.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('map.index');
    }

    /**
     * Show the form for creating a new map.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('index', compact('themes'));
    }

    /**
     * Store a newly created map in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $map                = new Map($request->all());
        $map->user_id       = $request->user()->id;
        $map->responsiveMap = $request->has('responsiveMap');

        $options         = (array)$map->mapOptions;
        $options         = $this->cleanMapOptions($request, $options);
        $map->mapOptions = (object)$options;

        $map->save();

        return redirect()->route('map.index');
    }

    /**
     * Display the specified map.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Map $map)
    {
        $this->authorize($map);
    }

    /**
     * Show the form for editing the specified map.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Map $map)
    {
        $this->authorize($map);

        return view('index', compact('map'));
    }

    /**
     * Update the specified map in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Map $map)
    {
        $this->authorize($map);

        $map->title         = $request->has('title') ? $request->input('title') : $map->title;
        $map->apiKey        = $request->has('apiKey') ? $request->input('apiKey') : $map->apiKey;
        $map->mapContainer  = $request->has('mapContainer') ? $request->input('mapContainer') : $map->mapContainer;
        $map->latitude      = $request->has('latitude') ? $request->input('latitude') : $map->latitude;
        $map->longitude     = $request->has('longitude') ? $request->input('longitude') : $map->longitude;
        $map->markers       = $request->has('markers') ? $request->input('markers') : $map->markers;
        $map->mapOptions    = $request->has('mapOptions') ? $request->input('mapOptions') : $map->mapOptions;
        $map->responsiveMap = $request->has('responsiveMap');
        $map->theme_id      = $request->has('theme_id') ? $request->theme_id : $map->theme_id;

        $options         = (array)$map->mapOptions;
        $options         = $this->cleanMapOptions($request, $options);
        $map->mapOptions = (object)$options;

        $map->save();

        return redirect()->route('map.edit', $map);
    }

    /**
     * Remove the specified map from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Map $map)
    {
        $this->authorize($map);
        $map->delete();

        return redirect()->route('home');
    }

    /**
     * @param Request $request
     * @param         $options
     * @return array
     */
    protected function cleanMapOptions(Request $request, $options)
    {
        foreach ($options as &$v)
        {
            if ($v == "on")
            {
                $v = 'true';
            }
        }
        $options['doubleClickZoom']       = $request->has("mapOptions.doubleClickZoom") ? 'true' : 'false';
        $options['clickableIcons']        = $request->has("mapOptions.clickableIcons") ? 'true' : 'false';
        $options['draggable']             = $request->has("mapOptions.draggable") ? 'true' : 'false';
        $options['showFullScreenControl'] = $request->has("mapOptions.showFullScreenControl") ? 'true' : 'false';
        $options['keyboardShortcuts']     = $request->has("mapOptions.keyboardShortcuts") ? 'true' : 'false';
        $options['mapMakerTiles']         = $request->has("mapOptions.mapMakerTiles") ? 'true' : 'false';
        $options['showMapTypeControl']    = $request->has("mapOptions.showMapTypeControl") ? 'true' : 'false';
        $options['showScaleControl']      = $request->has("mapOptions.showScaleControl") ? 'true' : 'false';
        $options['scrollWheel']           = $request->has("mapOptions.scrollWheel") ? 'true' : 'false';
        $options['showStreetViewControl'] = $request->has("mapOptions.showStreetViewControl") ? 'true' : 'false';
        $options['showZoomControl']       = $request->has("mapOptions.showZoomControl") ? 'true' : 'false';

        return $options;
    }
}
