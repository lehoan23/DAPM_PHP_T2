<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return response()->file(public_path('reset-password.html'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);

        $resetData = DB::table('password_reset_tokens')->where('token', $request->input('token'))->first();

        if (!$resetData) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $user = User::where('email', $resetData->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);
        DB::table('password_reset_tokens')->where('token', $request->input('token'))->delete();

        return response()->json(['message' => 'Password has been reset successfully.']);
    }
}