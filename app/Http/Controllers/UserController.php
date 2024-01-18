<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function chooseMaritalStatus()
    {
        return view('users.choose_marital_status');
    }
}
