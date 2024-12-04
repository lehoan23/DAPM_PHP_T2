<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Mail\MyTestEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\User\PaymentController;

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
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//login by google account
Route::middleware(['web'])->group(function () {
    Route::get('/auth/google',[LoginGoogleController::class,'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [LoginGoogleController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::get('/api/auth/createToken', [LoginGoogleController::class, 'createToken'])->name('auth.createToken');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/loading', function () {
    $id = request('id'); // Lấy id từ query string
    return view('loading', compact('id')); // Truyền id vào view
})->name('loading');

Route::get('/payment', [PaymentController::class, 'paymentCreate']);
Route::post('/create-payment', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/checkout', [PaymentController::class, 'checkoutPayment'])->name('vnpay.return');