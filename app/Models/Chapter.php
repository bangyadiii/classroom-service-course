<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "course_id"
    ];
    public function lessons(){
        return $this->hasMany(Lesson::class);
    }

}
