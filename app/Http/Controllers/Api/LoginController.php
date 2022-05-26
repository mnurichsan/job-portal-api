<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseBuilder;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(),[
                'email'      => 'required',
                'password'   => 'required' 
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }

            if (! $token = auth()->attempt(['email' => $request->email,'password' => $request->password])) {
              
                return ResponseBuilder::error(message:'Invalid credentials!',statusCode : 401);
            }
    
            $user = auth()->user();
           
            $token = $user->createToken('authToken')->plainTextToken;
    
            $dataUser = User::findOrFail($user->id);
            $dataUser['token'] = $token;

            return ResponseBuilder::success('User Fetch Successfully',$dataUser,200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
    
}
