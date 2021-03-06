<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helpers\ResponseBuilder;
use App\Helpers\ImageHelper;
use App\Helpers\FileHelper;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    // register
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


            $companyCreated = Company::updateOrCreate(['user_id' => Auth::user()->id],$data);
            User::find(Auth::user()->id)->update([
                'role' => 'Company'
            ]);

            return ResponseBuilder::success('Company Created Or Updated Successfully',$companyCreated,201);


        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }


    }

      //after register, candidate must fill data about themselves
      public function candidateDetailRegister(Request $request)
      {
            try {

                $validator = Validator::make($request->all(),[
                    'first_name'     => 'required',
                    'last_name'      => 'required',
                    'phone'          => 'required',
                    'location'       => 'required',
                    'gender'         => 'required',
                    'nationality'    => 'required',
                    'image'          => 'max:2048|mimes:jpg,png,jpeg',
                    'resume'         => 'max:2048',
                    'age'            => 'required'
                ]);


                if($validator->fails()){
                    return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
                }

                $data = [
                    'first_name'         => $request->first_name,
                    'last_name'          => $request->last_name,
                    'phone'              => $request->phone,
                    'location'           => $request->location,
                    'gender'             => $request->gender,
                    'nationality'        => $request->nationality,
                    'about'              => $request->about,
                    'age'                => $request->age
                ];



                if($request->has('image'))
                {
                    $data['image'] =  ImageHelper::uploadImage($request->image,"candidate");
                }

                if($request->has('resume'))
                {
                    $data['resume'] =  FileHelper::uploadFile($request->resume,"resume");
                }


                $candidateCreated = Candidate::updateOrCreate(['user_id' => Auth::user()->id],$data);
                User::find(Auth::user()->id)->update([
                    'role' => 'Candidate'
                ]);

                return ResponseBuilder::success('Candidate User Created Or Updated Successfully',$candidateCreated,201);


            } catch (\Exception $e) {
                return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
            }
      }

}
