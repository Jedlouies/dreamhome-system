<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Issue 1 fix: redirect logged-in users
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Issue 7 fix: pull real properties from DB
        $properties = DB::table(DB::raw("get_properties_by_branch(NULL, NULL) as p"))->get();

        return view('welcome', compact('properties'));
    }
}