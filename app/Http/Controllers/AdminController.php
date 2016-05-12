<?php

namespace App\Http\Controllers;

use App\Icon;
use App\Theme;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
        $this->middleware('pjax', ['only' => ['index']]);

    }

    public function index()
    {
        return view('admin.index');
    }

    public function addMarkerIcon(Request $request)
    {
        $icon       = Icon::firstOrCreate(['url' => $request->input('iconURL')]);
        $icon->name = $request->input('iconName');
    }

    public function AZPopulate()
    {

        $files = array_values(array_diff(scandir(__DIR__ . '/../../../public/icons/svgs'), ['.', '..']));
        for ($i = 0; $i < count($files); $i++)
        {
            $file = $files[$i];
            $name = str_replace('.svg', '', basename($file));
            $url  = url("icons/svgs/{$file}");
            try
            {
                Icon::firstOrCreate([
                    'url'  => "{$url}",
                    'name' => "{$name}",
                ]);
            } catch (QueryException $e)
            {

            }
        }


        foreach (range('A', 'Z') as $letter)
        {

            Icon::firstOrCreate([
                'url'  => "//www.google.com/mapfiles/marker{$letter}.png",
                'name' => "The letter {$letter}",
            ]);
            Icon::firstOrCreate([
                'url'  => "//mts.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-a.png&text={$letter}&psize=11&color=ff000000&ax=44&ay=48&scale=1",
                'name' => "The letter {$letter} in a green waypoint marker",
            ]);
            Icon::firstOrCreate([
                'url'  => "//mts.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text={$letter}&psize=11&color=ff000000&ax=44&ay=48&scale=1",
                'name' => "The letter {$letter} in a red waypoint marker",
            ]);
        }

        foreach (['red', 'blue', 'yellow', 'green', 'orange', 'purple', 'pink'] as $colour)
        {
            Icon::firstOrCreate([
                'url'  => "//www.google.com/intl/en_us/mapfiles/ms/micons/{$colour}-dot.png",
                'name' => "A {$colour} dot",
            ]);

            Icon::firstOrCreate([
                'url'  => "//www.google.com/intl/en_us/mapfiles/ms/micons/{$colour}.png",
                'name' => "A {$colour} marker",
            ]);

        }
        foreach (['red', 'orange', 'purple', 'pink', 'ltblu', 'wht', 'ylw', 'grn', 'blu'] as $colour)
        {


            Icon::firstOrCreate([
                'url'  => "//maps.google.com/mapfiles/kml/paddle/{$colour}-blank.png",
                'name' => "A {$colour} paddle",
            ]);
            Icon::firstOrCreate([
                'url'  => "//maps.google.com/mapfiles/kml/paddle/{$colour}-circle.png",
                'name' => "A {$colour} paddle",
            ]);
            Icon::firstOrCreate([
                'url'  => "//maps.google.com/mapfiles/kml/paddle/{$colour}-diamond.png",
                'name' => "A {$colour} paddle",
            ]);
            Icon::firstOrCreate([
                'url'  => "//maps.google.com/mapfiles/kml/paddle/{$colour}-square.png",
                'name' => "A {$colour} paddle",
            ]);
            Icon::firstOrCreate([
                'url'  => "//maps.google.com/mapfiles/kml/paddle/{$colour}-stars.png",
                'name' => "A {$colour} paddle",
            ]);
        }


        $files = array_values(array_diff(scandir(__DIR__ . '/../../../public/icons/pngs'), ['.', '..']));
        for ($i = 0; $i < count($files); $i++)
        {
            $file = $files[$i];
            $name = str_replace('.png', '', basename($file));
            $url  = url("icons/pngs/{$file}");
            try
            {
                Icon::firstOrCreate([
                    'url'  => "{$url}",
                    'name' => "{$name}",
                ]);
            } catch (QueryException $e)
            {

            }
        }


        return redirect()->back();
    }
}
