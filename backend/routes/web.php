<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Mail\MyTestEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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

// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/', function () {
//         return view('welcome');
//     });
//     Route::group(['middleware' => 'auth.Admin'], function () {
//         Route::get('/admin', function () {
//             return view('Pages.Admin.users.index');
//         });
//     });
//    });
Route::get('/', function () {
    return view('welcome');
});
Route::get('/testemail', function () {
    $name = "funny Coder";
    Mail::to('levanbinhdinh22@gmail.com')->send(new MyTestEmail($name));
});