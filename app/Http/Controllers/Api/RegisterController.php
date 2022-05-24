<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    //company register
    public function register(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        

    }

    //after register, company must fill data about their company
    public function companyDetailRegister(Request $request){

        $this->validate($request,[

        ]);
    }

}
