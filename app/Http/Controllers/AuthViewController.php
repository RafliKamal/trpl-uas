<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthViewController extends Controller
{
     public function login()
    {
        return view('login'); // akan mencari file resources/views/auth.blade.php
    }

     public function Register()
    {
        return view('register'); // akan mencari file resources/views/auth.blade.php
    }
}
