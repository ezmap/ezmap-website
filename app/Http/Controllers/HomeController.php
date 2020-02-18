<?php

namespace App\Http\Controllers;

use App\Icon;
use Faker\Provider\Uuid;
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
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->user()->apiKey === "")
    {
      $request->user()->apiKey = Uuid::uuid();
      $request->user()->save();
    }

    return redirect()->route('map.index');
  }

  /**
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   */
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

  /**
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   */
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

  public function renewApiKey(Request $request)
  {
    $request->user()->apiKey = Uuid::uuid();
    $request->user()->save();

    return redirect()->back();
  }
}
