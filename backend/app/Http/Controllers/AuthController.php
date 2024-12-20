<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        // Tạo token
        $verificationToken = Str::random(64);
        $user = new User;
        $user->username = request()->username;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->role_id = 3; // User mặc định
        $user->email_verification_token = $verificationToken;
        $user->save();
        // Tạo token

        $actionURL = url('api/auth/verify-email?token=' . $verificationToken);

        // Gửi email xác nhận
        Mail::to($user->email)->send(new VerifyEmail($actionURL));

        return response()->json(['message' => 'Registration successful! Please verify your email.'], 201);

    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        // Kiểm tra thông tin đăng nhập
        if (!$token = auth()->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Lấy thông tin user hiện tại
        $user = auth()->user();
        if ($user->email_verification_token != null) {
            return response()->json(['error' => 'Unauthorized: Email is not verified'], 401);
        }
        // Kiểm tra vai trò của user
        switch ($user->role_id) {
            case 1:
                $roleMessage = 'Welcome Admin';
                break;
            case 2:
                $roleMessage = 'Welcome Creator';
                break;

            case 3:
                $roleMessage = 'Welcome User';
                break;

            default:
                // Vai trò không hợp lệ
                return response()->json(['error' => 'Forbidden: Invalid role'], 403);
        }

        // Tạo refresh token
        $refreshToken = $this->createRefreshToken();

        // Trả về token và thông báo vai trò
        return $this->respondWithToken($token, $refreshToken, $roleMessage);
    }

    // /**

    //  * Create a new controller instance.

    //  *

    //  * @return void

    //  */

    // public function redirectToGoogle()
    // {

    //     return Socialite::driver('google')->redirect();

    // }

    // /**

    //  * Create a new controller instance.

    //  *

    //  * @return void

    //  */

    // public function handleGoogleCallback()
    // {

    //     try {

    //         $user = Socialite::driver('google')->user();

    //         $finduser = User::where('google_id', $user->id)->first();
    //         $token = JWTAuth::fromUser($finduser);
    //         if ($finduser) {

    //             Auth::login($finduser);
    //             // Trả về token
    //             return response()->json(['token' => $token]);

    //         } else {

    //             $newUser = User::updateOrCreate(['email' => $user->email], [

    //                 'username' => $user->name,

    //                 'google_id' => $user->id,

    //                 'password' => bcrypt('123456789'),
    //                 'role_id' => 3,

    //             ]);

    //             Auth::login($newUser);
    //             dd($token);
    //             // Trả về token
    //             // Trả về token cho frontend
    //             return response()->json([
    //                 'access_token' => $token,
    //                 'token_type' => 'bearer',
    //                 'expires_in' => JWTAuth::factory()->getTTL() * 60,
    //             ]);

    //         }

    //     } catch (Exception $e) {

    //         dd($e->getMessage());

    //     }

    // }

    public function me()
    {
        try {
            //code...
            return response()->json(auth('api')->user());
        } catch (JWTException $e) {
            //throw $th;
            return response()->json(['error' => $e->getMessage()], 401);
        }

    }
    //khi test cũng phải đưa cái token vào mới logout được
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $refreshToken = request()->refresh_token;
        try {
            //code...
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            //xu ly cap lai token moi
            //->lay thong tin user
            $user = User::find($decoded['sub']);
            if (!$user) {
                return response()->json(['error' => "User not found"], 404);
            }
            auth()->invalidate();
            $token = auth()->login($user);
            $refreshToken = $this->createRefreshToken();
            $roleMessage = "refresh done";
            return $this->respondWithToken($token, $refreshToken, $roleMessage);
        } catch (JWTException $e) {
            //throw $th;
            return response()->json([$e->getMessage()], 500);
        }

    }

    protected function respondWithToken($token, $refreshToken, $roleMessage)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'role_message' => $roleMessage,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    private function createRefreshToken()
    {
        $data = [
            'sub' => auth()->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl'),
        ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }

    public function updateProfile(Request $request)
    {
        // Lấy thông tin user hiện tại từ token
        $currentUser = auth('api')->user();
        $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email',
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
        ],
            [
                'username.required' => 'Hãy nhập Username',
                'email.required' => 'Nhập Email',
                'email.email' => 'Email không hợp lệ',
                'phone_number.required' => 'Nhập phone number',
                'phone_number.max' => 'Kí tự tối đa là 255',
            ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);
        return response()->json(['message' => 'User updated successfully']);
    }

    public function sendMailResetPassword(Request $request)
    {
        // Lấy thông tin user hiện tại từ token
        $currentUser = auth('api')->user();
        // $id = auth('api')->user()->id;

        if (!$currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $email = $currentUser->email;
        $token = Str::random(60);
        $actionURL = url('api/auth/verify-password?token=' . $token);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
        // Gửi email xác nhận
        Mail::to($email)->send(new VerifyEmail($actionURL));
        return response()->json(['message' => 'Please verify your email.'], 201);
    }

    public function ResetPassword(Request $request)
    {
             // Lấy thông tin user hiện tại từ token
             $currentUser = auth('api')->user();
             $id = auth('api')->user()->id;
     
             if (!$currentUser) {
                 return response()->json(['error' => 'Unauthorized'], 401);
             }
             $validated = $request->validate([
                 'password' => 'required|confirmed|min:8',
             ],
                 [
                     'password.required' => 'Hãy nhập password',
                     'password.confirmed' => 'Trung password',
                     'password.min:8' => 'Password phai dai hon 8 ki tu',
                 ]);
     
             $user = User::find($id);
             if (!$user) {
                 return response()->json(['error' => 'User not found'], 404);
             }
     
             $user->update([
                 'password' => bcrypt(request()->password),
             ]);
             return response()->json(['message' => 'Password updated successfully']);
    }

}