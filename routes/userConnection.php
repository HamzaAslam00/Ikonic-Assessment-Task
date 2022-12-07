<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;

Route::resource('requests', RequestController::class);