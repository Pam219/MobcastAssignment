<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dataController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [dataController::class,'index']);
