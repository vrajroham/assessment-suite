<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function getDatabase(Request $request)
    {
        return view('config.home');
    }
}
