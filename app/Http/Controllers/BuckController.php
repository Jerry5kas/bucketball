<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BuckController extends Controller
{
    public function setup(Request $request) {
        $buckets = $request->input('buckets');
        $balls = $request->input('balls');

        Session::put('buckets', $buckets);
        Session::put('balls', $balls);
        Session::put('empty', $buckets); // initially, all empty
        return redirect('/suggest');
    }

}
