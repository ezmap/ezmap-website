<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

use App\Http\Requests;

class SnazzyMapsController extends Controller
{
    public function populateThemes(Request $request)
    {

        $endpoint = 'https://snazzymaps.com/explore.json?key=' . env('SNAZZY_KEY');
        $endpoint .= ($request->has('tag')) ? "&tag={$request->input('tag')}" : '';
        $endpoint .= ($request->has('color')) ? "&color={$request->input('color')}" : '';
        $endpoint .= ($request->has('sort')) ? "&sort={$request->input('sort')}" : '';
        $endpoint .= ($request->has('page')) ? "&page={$request->input('page')}" : '';
        $endpoint .= ($request->has('pageSize')) ? "&pageSize={$request->input('pageSize')}" : '';

        $snazzyThemes = json_decode(file_get_contents($endpoint), false);

        foreach ($snazzyThemes->styles as $theme)
        {
            $snazzyTheme              = Theme::firstOrCreate(['snazzy_id' => $theme->id]);
            $snazzyTheme->json        = json_encode(json_decode($theme->json));
            $snazzyTheme->description = $theme->description;
            $snazzyTheme->author      = $theme->createdBy;
            $snazzyTheme->imageUrl    = $theme->imageUrl;
            $snazzyTheme->colors      = $theme->colors;
            $snazzyTheme->name        = $theme->name;
            $snazzyTheme->tags        = $theme->tags;
            $snazzyTheme->url         = $theme->url;
            $snazzyTheme->save();
        }

        return redirect()->back();
    }
}
