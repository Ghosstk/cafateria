<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::name('cafateria.')->group(function (){
    Route::get('/', [Controller::class,'index'])->name('index');
    Route::post('/', [Controller::class,'store'])->name('store');
});
