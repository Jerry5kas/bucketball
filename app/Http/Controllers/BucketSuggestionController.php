<?php

namespace App\Http\Controllers;

use App\Models\Ball;
use App\Models\BallPlacement;
use App\Models\Bucket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BucketSuggestionController extends Controller
{
    public function index()
    {
        $balls = Ball::all();
        $buckets = Bucket::all();
        return view('home', compact('balls', 'buckets'));
    }


    public function suggest(Request $request)
    {
        $sessionId = session()->getId();
        $ballQuantities = $request->input('balls', []);

        $buckets = Bucket::orderByDesc('empty_volume')->get();
        $ballsMap = Ball::all()->keyBy('color');

        $suggestions = [];

        foreach ($ballQuantities as $color => $qty) {
            if ($qty <= 0) continue;

            $ball = $ballsMap[$color];
            $remaining = $qty;
            $ballVolume = $ball->volume;

            foreach ($buckets as $bucket) {
                $canFit = floor($bucket->empty_volume / $ballVolume);
                if ($canFit <= 0) continue;

                $toPlace = min($canFit, $remaining);
                $bucket->empty_volume -= $toPlace * $ballVolume;
                $bucket->save();

                BallPlacement::create([
                    'bucket_id' => $bucket->id,
                    'ball_id' => $ball->id,
                    'quantity' => $toPlace,
                    'session_id' => $sessionId
                ]);

                $suggestions[] = [
                    'bucket' => $bucket->name,
                    'color' => $ball->color,
                    'quantity' => $toPlace,
                ];

                $remaining -= $toPlace;
                if ($remaining <= 0) break;
            }

            if ($remaining > 0) {
                $suggestions[] = [
                    'bucket' => null,
                    'color' => $ball->color,
                    'quantity' => $remaining,
                ];
            }
        }

        return redirect()->route('home')->with('suggestions', $suggestions);
    }

    public function storeBucket(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:buckets,name',
            'total_volume' => 'required|numeric|min:0.1',
        ]);

        Bucket::create([
            'name' => $validated['name'],
            'total_volume' => $validated['total_volume'],
            'empty_volume' => $validated['total_volume'],
        ]);

        return redirect()->route('home')->with('success', 'Bucket added successfully.');
    }

    public function storeBall(Request $request)
    {
        $validated = $request->validate([
            'color' => 'required|string|unique:balls,color',
            'volume' => 'required|numeric|min:0.1',
        ]);

        Ball::create($validated);

        return redirect()->route('home')->with('success', 'Ball added successfully.');
    }



    public function resetVolumes(Request $request)
    {
        Bucket::query()->update([
            'empty_volume' => DB::raw('total_volume')
        ]);
        return redirect()->route('home')->with('success', 'All bucket volumes have been reset.');
    }


}
