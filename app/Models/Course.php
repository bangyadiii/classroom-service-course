<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'certificate',
        "type",
        "tumbnail",
        "price",
        "status",
        "level",
        "mentor_id"


    ];
    public function mentor(){
        return $this->belongsTo(Mentors::class);
    }
    public function chapters(){
        return $this->hasMany(Chapter::class);
    }
    public function images(){
        return $this->hasMany(ImageCourse::class);
    }
}
