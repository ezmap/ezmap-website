<?php

namespace App\Http\Controllers;

use App\Models\Icon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   */
  public function __construct()
  {
    // Middleware applied via route definitions
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
      'confirmation' => [
        'required',
        'string',
        Rule::in(['delete my account']),
      ],
    ], [
      'confirmation.in' => 'You must type exactly "delete my account" to confirm account deletion.',
    ]);

    $user = $request->user();

    // Logout first (before deleting, since logout saves the user's remember token)
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Hard delete all user's maps (bypass soft delete)
    $user->maps()->withTrashed()->forceDelete();
    
    // Delete all user's icons
    $user->icons()->delete();

    // Delete the user account
    $user->delete();
    
    return redirect('/')->with('success', 'Your account has been permanently deleted.');
  }
}
