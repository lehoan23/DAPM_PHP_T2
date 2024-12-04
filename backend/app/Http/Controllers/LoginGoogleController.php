<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class LoginGoogleController extends Controller
{
    //
    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();

    }

    /**

     * Create a new controller instance.

     *

     * @return void

     */

     public function handleGoogleCallback()
     {
         try {
             $user = Socialite::driver('google')->user();
             $finduser = User::where('google_id', $user->id)->first();
     
             if ($finduser) {
                 Auth::login($finduser);
     
                 // Kiểm tra nếu người dùng đã đăng nhập
                 if (Auth::check()) {
                    $user = Auth()->user();
                    $id = $user->id;
                    return redirect()->to('http://127.0.0.1:8000/loading?id=' . $id);

                 } else {
                     return response()->json(['error' => 'Đăng nhập không thành công.']);
                 }
             } else {
                 $newUser = User::updateOrCreate(['email' => $user->email], [
                     'username' => $user->name,
                     'google_id' => $user->id,
                     'password' => bcrypt('123456789'),
                     'role_id' => 3,
                 ]);
     
                 Auth::login($newUser);
     
                 // Kiểm tra nếu người dùng đã đăng nhập
                 if (Auth::check()) {
                    $user = Auth()->user();
                    $id = $user->id;
                    return redirect()->route('loading', ['id' => $id]);
                 } else {
                     return response()->json(['error' => 'Đăng nhập không thành công.']);
                 }
             }
         } catch (Exception $e) {
             return response()->json($e->getMessage());
         }
     }

     public function createToken(Request $request)
    {
         // Lấy giá trị của id từ query string
    $id = $request->query('id'); // hoặc $request->get('id');

    // Kiểm tra nếu id tồn tại
    if ($id) {
        $user = User::find($id); // Tìm người dùng với id tương ứng

        if ($user) {
            $token = JWTAuth::fromUser($user); // Tạo token từ người dùng

            return response()->json([
                'success' => true,
                'access_token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(['error' => 'Người dùng không tồn tại'], 404);
        }
    } else {
        return response()->json(['error' => 'ID không được cung cấp'], 400);
    }
    }
    
}