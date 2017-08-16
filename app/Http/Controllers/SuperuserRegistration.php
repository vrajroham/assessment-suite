<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SuperuserRegistration extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function registrationStep1()
    {
        $user = User::getUserFromUsertype('superadmin');
        if ($user) {
            return redirect('/');
        }

        return view('superuser.registration.step-1');
    }

    public function saveRegistrationStep1(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'usertype' => 'required|string',
            ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->usertype = $request->usertype;
        $user->save();
        $user->assignRole('superadmin');

        return redirect('/');
    }
}
