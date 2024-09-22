<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('{any}', function () {
    return view('welcome');  // This should point to your React app
})->where('any', '.*');
