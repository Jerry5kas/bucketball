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


    public function suggestPlacement(Request $request)
    {
        $request->validate([
            'balls' => 'required|array',
            'balls.*.color' => 'required|string|exists:balls,color',
            'balls.*.quantity' => 'required|integer|min:1'
        ]);

        $sessionId = session()->getId();
        $buckets = Bucket::orderByDesc('empty_volume')->get();
        $ballsInput = collect($request->balls);

        $ballsMap = Ball::whereIn('color', $ballsInput->pluck('color'))->get()->keyBy('color');
        $suggestions = [];

        foreach ($ballsInput as $ball) {
            $ballObj = $ballsMap[$ball['color']];
            $remaining = $ball['quantity'];
            $ballVolume = $ballObj->volume;

            foreach ($buckets as $bucket) {
                $canFit = floor($bucket->empty_volume / $ballVolume);
                if ($canFit <= 0) continue;

                $toPlace = min($canFit, $remaining);
                if ($toPlace > 0) {
                    $bucket->empty_volume -= $toPlace * $ballVolume;
                    $bucket->save();

                    BallPlacement::create([
                        'bucket_id' => $bucket->id,
                        'ball_id' => $ballObj->id,
                        'quantity' => $toPlace,
                        'session_id' => $sessionId
                    ]);

                    $suggestions[] = [
                        'bucket' => $bucket->name,
                        'color' => $ballObj->color,
                        'quantity' => $toPlace
                    ];

                    $remaining -= $toPlace;
                }

                if ($remaining <= 0) break;
            }

            if ($remaining > 0) {
                $suggestions[] = [
                    'bucket' => null,
                    'color' => $ballObj->color,
                    'quantity' => $remaining,
                    'message' => 'Not enough space to place all balls.'
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'suggestions' => $suggestions
        ]);
    }


    public function resetVolumes(Request $request)
    {
        Bucket::query()->update([
            'empty_volume' => DB::raw('total_volume')
        ]);
        return redirect()->route('home')->with('success', 'All bucket volumes have been reset.');
    }


}
