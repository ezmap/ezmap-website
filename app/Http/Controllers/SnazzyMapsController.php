<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

use App\Http\Requests;

class SnazzyMapsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }




    public function populateThemes(Request $request)
    {

        $endpoint = 'https://snazzymaps.com/explore.json?key=' . env('SNAZZY_KEY');
        $endpoint .= ($request->has('tag')) ? "&tag={$request->input('tag')}" : '';
        $endpoint .= ($request->has('color')) ? "&color={$request->input('color')}" : '';
        $endpoint .= ($request->has('sort')) ? "&sort={$request->input('sort')}" : '';
        $endpoint .= ($request->has('page')) ? "&page={$request->input('page')}" : '';
        $endpoint .= ($request->has('pageSize')) ? "&pageSize={$request->input('pageSize')}" : '';

        $snazzyThemes = json_decode(file_get_contents($endpoint), false);
        foreach ($snazzyThemes->styles as $theme) {
            $snazzyTheme = Theme::firstOrCreate(['snazzy_id'=> $theme->id]);
            $snazzyTheme->name = $theme->name;
            $snazzyTheme->description = $theme->description;
            $snazzyTheme->url = $theme->url;
            $snazzyTheme->imageUrl = $theme->imageUrl;
            $snazzyTheme->json = $theme->json;
            $snazzyTheme->author = $theme->createdBy;
            $snazzyTheme->save();
        }
        
        return redirect()->back();

    }
}