<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
// Route::get('/login', function () {
//     return view('SignIn.index');
// })->name('login');

// // Route::get('/manageuser', function () {
// //     return view('Pages.Admin.users.index');
// // })->name('login');
// Route::get('/admin', [AuthController::class, 'index'])->middleware('auth:api')->name('admin');