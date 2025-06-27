<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use App\Models\Bucket;
use App\Models\Ball;

class BucketAndBallSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('ball_placements')->truncate();
        DB::table('buckets')->truncate();
        DB::table('balls')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed buckets
        $buckets = [
            ['name' => 'A', 'total_volume' => 20],
            ['name' => 'B', 'total_volume' => 18],
            ['name' => 'C', 'total_volume' => 12],
            ['name' => 'D', 'total_volume' => 10],
            ['name' => 'E', 'total_volume' => 8],
        ];

        foreach ($buckets as $b) {
            Bucket::create([
                'name' => $b['name'],
                'total_volume' => $b['total_volume'],
                'empty_volume' => $b['total_volume'],
            ]);
        }

        // Seed balls
        $balls = [
            ['color' => 'Pink', 'volume' => 2.5],
            ['color' => 'Red', 'volume' => 2.0],
            ['color' => 'Blue', 'volume' => 1.0],
            ['color' => 'Orange', 'volume' => 0.8],
            ['color' => 'Green', 'volume' => 0.5],
        ];

        foreach ($balls as $b) {
            Ball::create($b);
        }
    }
}
