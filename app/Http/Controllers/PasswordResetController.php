<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PasswordResetController extends Controller
{
    //
    public function index()
    {

    }

    public function passwordResetForm(Request $request)
    {
        $username = $request->username;

        $user = User::where('email',$username)->first();

        if($user){
            return view('app.accounts.password-reset.form',['username'=> $user->email]);
        }else{
            echo 'Invalid User Account. Please contact IT for technical support.';
        }
    }
}
