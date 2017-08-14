<?php

namespace App\Http\Controllers;

class SuperuserRegistration extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function registrationStep1()
    {
        return redirect('/');
    }
}
