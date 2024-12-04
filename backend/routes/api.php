<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Creator\CategoryController;
use App\Http\Controllers\Creator\CreatorController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\User\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Những route authentication : api/auth/...
//dùng chung cho tất cả các user
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
    Route::put('/update-me', [AuthController::class, 'updateProfile'])->middleware('auth:api');
    Route::put('/reset_password', [AuthController::class,'ResetPassword'])->middleware('auth:api');
    Route::get('/verify-email', function (Request $request) {
        // Lấy token từ URL
        $token = $request->query('token');
    
        // Kiểm tra token trong cơ sở dữ liệu
        $user = DB::table('users')->where('email_verification_token', $token)->first();
    
        if ($user) {
            // Xác nhận email
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'email_verified_at' => Carbon::now(),
                    'email_verification_token' => null, // Xóa token sau khi xác nhận
                ]);
    
                return view('mail.verify-success');
        }
    
        return response('Invalid or expired token.', 400);
    });
    // Route::post('/send-mail/reset-password', [AuthController::class, 'sendMailResetPassword'])->middleware('auth:api');
    // Route::get('/verify-password', function (Request $request) {
    //     // Lấy token từ URL
    //     $token = $request->query('token');
    //     // Kiểm tra token trong cơ sở dữ liệu
    //     $password_reset_tokens = DB::table('password_reset_tokens')->where('token', $token)->first();
    //     if ($password_reset_tokens) {
    //         // Xác nhận email
    //         DB::table('password_reset_tokens')
    //             ->where('email', $password_reset_tokens->email)
    //             ->update([
    //                 'token' => null, // Xóa token sau khi xác nhận
    //             ]);
    //             return view('mail.verify-change-success');
    //     }
    //     return response('Invalid or expired token.', 400);
    // });
    //Nhungx route có role là user
    Route::group(['middleware' => ['auth:api', 'auth.user']], function () {
        Route::get('/get-registered-project', [PaymentController::class, 'getRegisteredProject']);
        Route::post('/create-payment', [PaymentController::class, 'createPayment']);
    });
    Route::group(['middleware' => ['auth:api','auth.admin-creator']],function(){
        //get my list project
        Route::get('/project', [CreatorController::class, 'getAllListProject']);
        Route::post('/project-create', [CreatorController::class, 'createProject']);
        Route::get('/project-edit/{id}', [CreatorController::class, 'getEditProject']);
        Route::put('/project-update/{id}', [CreatorController::class, 'updateProject']);
        Route::get('/getAllProject',[AdminController::class, 'getAllListProject']);
    });
    //Nhung route co role la creator : done
    Route::group(['middleware' => ['auth:api', 'auth.creator']], function () {
        //get my list project
        Route::get('/creator/project', [CreatorController::class, 'getAllListProject']);
        //Tao Project
        Route::post('/creator/project-create', [CreatorController::class, 'createProject']);
        //lấy chi tiết project theo id
        Route::get('/creator/project-edit/{id}', [CreatorController::class, 'getEditProject']);
        //lấy chi tiết pending project theo id
        // Route::get('/creator/pending_project-edit/{id}', [CreatorController::class, 'getEditPendingProject']);
        //update project da duyet
        Route::put('/creator/project-update/{id}', [CreatorController::class, 'updateProject']);
        //update project chua duyet
        // Route::put('/creator/pending_project-update/{id}', [CreatorController::class, 'updateProjectPending']);
    });

    //Nhung route co role la admin 
    Route::group(['middleware' => ['auth:api', 'auth.admin']], function () {
        //Duyet project
        Route::post('/admin/approve-project/{id}', [AdminController::class, 'approve']);
        //Tu choi project
        Route::post('/admin/reject-project/{id}', [AdminController::class, 'reject']);
        //Tao Project
        Route::post('/admin/project-create', [AdminController::class, 'createProject']);
        //lấy chi tiết project theo id
        Route::get('/admin/project-edit/{id}', [AdminController::class, 'getEditProject']);
        //update
        Route::put('/admin/project-update/{id}', [AdminController::class, 'updateProject']);
        //Xoa Project thong qua id
        Route::delete('/admin/project-delete/{id}', [AdminController::class, 'deleteProject']);
        //khoi phuc project bi xoa thong qua id
        Route::patch('/admin/project-restore/{id}', [AdminController::class, 'restoreProject']);

        //tao tai khoan nguoi dung
        Route::post('/admin/user-create', [AdminController::class, 'createUser']);
        Route::get('/admin/list-user', [AdminController::class, 'getListUser']);
        //lấy chi tiết user theo id
        Route::get('/admin/user-edit/{id}', [AdminController::class, 'getUserEdit']);
        //update user
        Route::put('/admin/user-update/{id}', [AdminController::class, 'updateUser']);
        //Xoa user thong qua id
        Route::delete('/admin/user-delete/{id}', [AdminController::class, 'deleteUser']);
        //khoi phuc user bi xoa thong qua id
        Route::patch('/admin/user-restore/{id}', [AdminController::class, 'restoreUser']);

    });

});

// những route không cần đăng nhập để xem: api/...
//1. Danh sách toàn bộ projects hiển thị ở trang chủ hoặc trang dự án
Route::get('/get-list-project', [GeneralController::class, 'getListProjects']);
//2. Chi tiết projects theo id khi click vào từng project
Route::get('/get-detail-project/{id}', [GeneralController::class, 'getDetailProjects']);
//3. Danh sach category
Route::get('/category', [CategoryController::class, 'getCategories']);
Route::get('/category/{id}', [CategoryController::class, 'getListProjectByCateId']);
// sendMail
Route::post('/reset-password', [GeneralController::class, 'sendMailResetPassword']);