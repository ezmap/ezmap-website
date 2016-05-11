<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Icon;
use App\Theme;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('map.index');
    }

    public function addNewIcon(Request $request)
    {
        $params = [
            'user_id' => $request->user()->id,
            'url'     => $request->input('newIconURL'),
            'name'    => $request->input('newIconName'),
        ];
        $icon   = Icon::firstOrCreate($params);
        if ($request->ajax())
        {
            return response()->json(['success' => true, 'icon' => $icon]);
        }

        return redirect()->back();
    }

    public function deleteIcon(Request $request)
    {
        $icon = Icon::where([
            'user_id' => $request->user()->id,
            'id'      => $request->input('icon-id'),
        ])->delete();

        if ($request->ajax())
        {
            return response()->json(['success' => true, 'icon' => ['id' => $request->input('icon-id')]]);
        }

        return redirect()->back();
    }
}
