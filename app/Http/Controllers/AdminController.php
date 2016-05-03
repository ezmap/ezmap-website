<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $themes = Theme::orderBy('name')->paginate(24);
        return view('admin.index', compact('themes'));
    }
}
