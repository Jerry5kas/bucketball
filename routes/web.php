<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

use App\Http\Controllers\BucketSuggestionController;

Route::get('/', [BucketSuggestionController::class, 'index'])->name('home');
Route::post('/suggest', [BucketSuggestionController::class, 'suggest'])->name('suggest');

Route::post('/reset-volumes', [BucketSuggestionController::class, 'resetVolumes'])->name('reset.volumes');
