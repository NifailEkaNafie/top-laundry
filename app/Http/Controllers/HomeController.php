<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        if (!session('jwt_token')) {
            return redirect('/login');
        }

        return view('home');
    }
}
