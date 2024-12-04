<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class GeneralController extends Controller
{
    //
    public function getListProjects(Request $request)
    {
        try {
            // Get the limit for pagination from the query string, default to 10 if not provided
            $limit = $request->input('limit', 10);
    
            // Query with joins and aggregate for support count
            $listProject = Project::where("projects.status", "=", "Active")
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('categories', 'projects.cate_id', '=', 'categories.id')
                ->join('images', 'projects.id', '=', 'images.project_id')
                ->leftJoin('payments', 'projects.id', '=', 'payments.project_id')
                ->select(
                    'projects.*',
                    'users.username as Creator',
                    'images.link as LinkImage',
                    'users.address as Address',
                    'users.email as Email',
                    'users.phone_number as PhoneNumber',
                    'categories.name as Category',
                    DB::raw('COUNT(payments.id) as LuotUngHo') 
                )
                ->groupBy(
                    'projects.id',
                    'projects.name',
                    'projects.description',
                    'projects.goal_amount',
                    'projects.collected_amount',
                    'projects.start_date',
                    'projects.end_date',
                    'projects.status',
                    'projects.create_by',
                    'projects.created_at',
                    'projects.updated_at',
                    'projects.deleted_at',
                    'projects.cate_id',
                    'users.username',
                    'images.link',
                    'users.address',
                    'users.email',
                    'users.phone_number',
                    'categories.name'
                )
                ->paginate($limit); // Paginate the result based on the limit
    
            // Check if the query returns any results
            if ($listProject->isNotEmpty()) {
                return response()->json($listProject);
            } else {
                // Return a 404 error if no projects are found
                return response()->json(['error' => 'Project not found'], 404);
            }
        } catch (\Exception $e) {
            // Return a 500 error with the exception message in case of failure
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function getDetailProjects(string $id)
    {
        try {
            // Query the complaint by ID, joining with the Resident table to get the requester's name
            $request = Project::where("projects.id", $id)
            ->join('users', 'projects.create_by', '=', 'users.id')
            ->join('categories', 'projects.cate_id', '=', 'categories.id')
->join('images', 'projects.id', '=', 'images.project_id')
            ->leftJoin('payments', 'projects.id', '=', 'payments.project_id')
            ->select(
                'projects.*',
                'users.username as Creator',
                'images.link as LinkImage',
                'users.address as Address',
                'users.email as Email',
                'users.phone_number as PhoneNumber',
                'categories.name as Category',
                DB::raw('COUNT(payments.id) as LuotUngHo') // Count the number of payments per project
            )
            ->groupBy(
                'projects.id',
                'projects.name',
                'projects.description',
                'projects.goal_amount',
                'projects.collected_amount',
                'projects.start_date',
                'projects.end_date',
                'projects.status',
                'projects.create_by',
                'projects.created_at',
                'projects.updated_at',
                'projects.deleted_at',
                'projects.cate_id',
                'users.username',
                'images.link',
                'users.address',
                'users.email',
                'users.phone_number',
                'categories.name'
            )
                ->first(); // Retrieve the first matching record

            // If the request exists, return it as a JSON response
            if ($request) {
                return response()->json($request);
            } else {
                // If not found, return a 404 error with a message
                return response()->json(['error' => 'Not found detail of project'], 404);
            }
        } catch (\Exception $e) {
            // Return a 500 error with the exception message in case of failure
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }

    public function sendMailResetPassword(Request $request)
    {
        try {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'This email does not exist.',
        ]);
    
        
            $email = $request->email;
            $user = User::where('email', $email)->first();
    
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
            $actionURL = url('http://127.0.0.1:5502/login.html');
            $password = Str::random(9);
    
            // Gửi email
            Mail::to($email)->send(new ResetPasswordMail($actionURL,$password));
    
            // Cập nhật mật khẩu
            $user->update([
                'password' => Hash::make($password),
            ]);
    
            return response()->json(['message' => 'Please check your email.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}