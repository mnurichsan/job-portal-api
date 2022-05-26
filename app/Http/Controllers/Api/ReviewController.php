<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function showCandidate($jobId)
    {
        try {

            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

             //check if company no complete the registration
            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            $job = Job::find($jobId);

            //check job is null or not
            if(!$job){
                return ResponseBuilder::error(message:'Job Not Found !',statusCode: 404);
            }

            $myCandidate = JobApplication::with('candidate','candidate.user','job')->where('job_id',$jobId)->get();

            return ResponseBuilder::success('My Candidate Fetch Successfully',$myCandidate,200);
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function acceptedToInterview(Request $request,$candidateId)
    {
        try {

            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'  => 'Interview',
                'review'  => $request->review
            ]);

            //Send Notification
            $this->sendWebNotification($applyCandidate,$candidate->user_id);

            return ResponseBuilder::success('Candidate Accepted To Interview Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function assessmentCandidate(Request $request,$candidateId)
    {
        try {
            
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Assessment',
                'assessment'  => $request->assessment
            ]);

            //Send Notification
            $this->sendWebNotification($applyCandidate,$candidate->user_id);

            return ResponseBuilder::success('Candidate Accepted To Assessment Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function acceptedToCompany(Request $request,$candidateId)
    {
        try {
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Accepted',
                'assessment'  => $request->assessment,
                'description' => $request->description
            ]);

            //Send Notification
            $this->sendWebNotification($applyCandidate,$candidate->user_id);

            return ResponseBuilder::success('Candidate Accepted To Company Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function rejected(Request $request,$candidateId)
    {
        try {
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Rejected',
                'assessment'  => $request->assessment,
                'description' => $request->description
            ]);

            //Send Notification
            $this->sendWebNotification($applyCandidate,$candidate->user_id);

            return ResponseBuilder::success('Candidate Rejected Successfully',$applyCandidate,201);
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function storeToken(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),[
                'device_token'  => 'required',
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }
           
            $userToken =['device_token'=>$request->device_token];
            auth()->user()->update($userToken);

            return ResponseBuilder::success('User Token Push Notif Updated Successfully',$userToken,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
 
    }

    public function sendWebNotification($data,$userId)
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $FcmToken = User::find($userId)->whereNotNull('device_token')->pluck('device_token')->all();

            if(!$FcmToken)
            {   
                return true;
            }
              
            $serverKey = 'AAAAf0z7lrw:APA91bHydvBIWA1fmfSnLFnn2U9zjprPSoyF4avqteuA35WqDeGwOJnTF8bRv1LjJbZf6fO5rGbMDoSqfHFp0ZTUsNBT1iRXJBRsLFKrH7OZ0R0lSRr3t1aZujhAL4zhi7gRObOwa_w2';
      
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => 'Status Lamaran Kamu Berubah',
                    "body" => 'Silahkan Cek Di Aplikasi',  
                ]
            ];
            $encodedData = json_encode($data);
        
            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];
        
            $ch = curl_init();
          
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }        
            // Close connection
            curl_close($ch);
            // FCM response
            return $result;

        } catch (\Exception $e) {
           return true;
        }
          
    }
}
