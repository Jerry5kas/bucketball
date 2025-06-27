<?php

use App\Http\Controllers\BucketSuggestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/suggest-placement', [BucketSuggestionController::class, 'suggestPlacement']);
