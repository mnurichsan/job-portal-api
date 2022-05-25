<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helpers\ResponseBuilder;
use App\Helpers\ImageHelper;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    //company register
    public function register(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(),[
                'name'       => 'required',
                'email'      => 'required',
                'password'   => 'required' 
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }

            $userCreated = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password)
            ]);

             $token = $userCreated->createToken('authToken')->plainTextToken;
             $dataUser = User::findOrFail($userCreated->id);
             $dataUser['token'] = $token;

            return ResponseBuilder::success('User Created Successfully',$dataUser,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }


    }

    //after register, company must fill data about their company
    public function companyDetailRegister(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),[
                'name'           => 'required',
                'description'    => 'required',
                'office_address' => 'required',
                'location'       => 'required',
                'industri'       => 'required',
                'image'          => 'max:2048|mimes:jpg,png,jpeg'
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }


            $data = [
                'user_id'            => Auth::user()->id, 
                'name'               => $request->name,
                'description'        => $request->description,
                'office_address'     => $request->office_address,
                'industri'           => $request->industri,
                'location'           => $request->location,
                'website'            => $request->website,
                'slug'               => Str::slug($request->name." ". rand(0,100))
            ];

            if($request->has('image'))
            {
                $data['image'] =  ImageHelper::uploadImage($request->image,"company");
            }


            $companyCreated = Company::create($data);

            return ResponseBuilder::success('Company Created Successfully',$companyCreated,201);


        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }


    }

}
