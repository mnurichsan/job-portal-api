<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        try {
            $jobs = Job::get();

            return ResponseBuilder::success(message:'Jobs Fetch Successfully',data:$jobs,statusCode:201);
            
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
}
