<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        "description",
        'certificate',
        "type",
        "tumbnail",
        "price",
        "status",
        "level",
        "mentor_id"
    ];

    protected $casts = [
        "created_at" => "date:Y-m-d H:m:s",
        "updated_at" => "date:Y-m-d H:m:s"
    ];

    public function mentor(){
        return $this->belongsTo(Mentors::class);
    }
    public function chapters(){
        return $this->hasMany(Chapter::class);
    }
    public function imageCourse(){
        return $this->hasMany(ImageCourse::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function myCourse(){
        return $this->hasMany(MyCourse::class);
    }


}
