<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','first_name','last_name','phone','location','gender','nationality','about','image','resume','age'];
    protected $appends  = ['image_url','resume_url'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/candidate/'.$this->image);
    }

    public function getResumeUrlAttribute()
    {
        return asset('storage/resume/'.$this->resume);
    }

    
}
