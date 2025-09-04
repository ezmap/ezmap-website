<?php

namespace App\Http\Controllers;

use App\Models\Icon;
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
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
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

  public function deleteAccount(Request $request)
  {
    $request->validate([
      'confirmation' => 'required|string|in:delete my account'
    ], [
      'confirmation.in' => 'You must type exactly "delete my account" to confirm account deletion.'
    ]);

    $user = $request->user();

    // Hard delete all user's maps (bypass soft delete)
    $user->maps()->withTrashed()->forceDelete();
    
    // Delete all user's icons
    $user->icons()->delete();

    // Delete the user account
    $user->delete();

    // Logout and redirect to home page
    auth()->logout();
    
    return redirect('/')->with('success', 'Your account has been permanently deleted.');
  }
}
