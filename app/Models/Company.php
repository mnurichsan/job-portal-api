<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','name','slug','image','description','location','office_address','industri','website'];
    protected $appends  = ['image_url'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/company/'.$this->image);
    }
}
