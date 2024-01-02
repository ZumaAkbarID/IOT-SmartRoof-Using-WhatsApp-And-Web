<?php

use App\Events\PusherTest;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainController::class, 'index']);

Route::get('/pusher-test', function () {
    return view('pusher-test');
});

Route::get('/pusher-send', function () {
    event(new PusherTest('hello world'));
    return "OK";
});

Route::get('/change-mode', [MainController::class, 'changeMode']);

Route::get('/testing', function () {
    // URL lokal yang akan diakses
    $url = 'http://192.168.1.69/?status=manual';

    // Lakukan HTTP GET request
    $response = Http::get($url);

    // Ambil data dari response
    $data = $response->json();

    return $data;
});
