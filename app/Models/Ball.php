<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ball extends Model
{
    /** @use HasFactory<\Database\Factories\BallFactory> */
    use HasFactory;
    protected $table = "balls";
    protected $guarded = [];
}
