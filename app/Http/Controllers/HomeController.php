<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }

        if ($user->hasRole('admin') || $user->hasRole('pharmacy') || $user->hasRole('doctor')) {
            return view('home');
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Unauthorized user role.');
    }
}