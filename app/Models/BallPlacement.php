<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BallPlacement extends Model
{
    /** @use HasFactory<\Database\Factories\BallPlacementFactory> */
    use HasFactory;

    protected $table = "ball_placements";
    protected $guarded = [];

}
