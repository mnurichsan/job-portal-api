<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','company_id','jobtype_id','category_id','description','salary','status'];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function jobType()
    {
        return $this->belongsTo(TypeJob::class,'jobtype_id','id');
    }

    public function category()
    {
        return $this->belongsTo(category::class,'category_id','id');
    }

    public function jobapply()
    {
        return $this->hasMany(JobApplication::class,'job_id','id');
    }
}
