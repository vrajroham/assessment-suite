<?php

namespace App\Http\Controllers;

use App\User;
use App\AdminProfile;
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
                'designation' => 'required|string',
                'mobile' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->usertype = 'superadmin';
        $user->save();
        $user->assignRole('superadmin');

        $profile = new AdminProfile();
        $profile->designation = $request->designation;
        $profile->email = $request->email;
        $profile->mobile = $request->mobile;
        $profile->users_id = $user->id;
        $profile->save();

        return redirect('/');
    }
}
