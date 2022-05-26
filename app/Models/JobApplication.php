<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = ['job_id','candidate_id','status','review','assessment','description'];

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id','id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class,'candidate_id','id');
    }
}
