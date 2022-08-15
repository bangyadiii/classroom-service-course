<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentors extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "profile",
        "email",
        "profession"
    ];

}
