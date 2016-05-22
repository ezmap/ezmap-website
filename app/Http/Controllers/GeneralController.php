<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

use App\Http\Requests;

class GeneralController extends Controller
{
    public function index(Request $request)
    {
        $sorts   = ['name', 'description', 'author', 'snazzy_id', 'id'];
        $sort    = 'name';
        $order   = 'asc';
        $appends = [];
        if ($request->has('sort') || $request->has('order'))
        {
            if ($request->has('sort'))
            {
                $sort = in_array($request->input('sort'), $sorts) ? $request->input('sort') : 'name';
                $appends['sort'] = $sort;
            }
            if ($request->has('order'))
            {
                $order = ($request->input('order') == 'true') ? 'desc' : 'asc';
                $appends['order'] = ($request->input('order') == 'desc') ? 'true' : 'false';
            }
        }
        $themes = Theme::orderBy($sort, $order)->paginate(24);
        if ($request->has('sort') || $request->has('order'))
        {
            $themes->appends($appends);
        }

        return view('index', compact('themes','sort','order'));
    }
    
}
