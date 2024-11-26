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

class GeneralController extends Controller
{
    //
    public function getListProjects(Request $request){
        try {
            // Get the limit for pagination from the query string, default to 10 if not provided
            $limit = $request->input('limit', 10);
            $listProject = Project::where("projects.status", "=", "Active")
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('images','projects.id', '=', 'images.project_id')
                ->select('projects.*', 'users.username as Creator','images.link as LinkImage')
                ->paginate($limit); // Paginate the result based on the limit
            if ($listProject->isNotEmpty()) {
                return response()->json($listProject);
            } else {
                // Return a 404 error if no requests are found
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
                ->join('images','projects.id', '=', 'images.project_id')
                ->select('projects.*', 'users.username as Creator','images.link as LinkImage')
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

}