<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Image;
// use App\Models\PendingProject;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    //
    public function getAllListProject(Request $request)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            $id = $currentUser->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Get the limit for pagination from the query string, default to 10 if not provided
            $limit = $request->input('limit', 10);
            $listProject = Project::where("projects.create_by", "=", $id)
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('images', 'projects.id', '=', 'images.project_id')
                ->select('projects.*', 'users.username as Creator', 'images.link as LinkImage')
                ->paginate($limit); // Paginate the result based on the limit

            // Check if the request list is not empty and return the data
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
    public function createProject(Request $request)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Validate input fields with rules for required fields and maximum lengths
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'goal_amount' => 'required',
                // 'collected_amount' => 'required',
                // 'status' => 'required',
                'start_date' => 'nullable|date',
                'end_date' => 'required|date|after:start_date',
                'cate_id' => 'required',
                'images.*' => 'required|image|mimes:jpg,png,jpeg,gif', // Validate file ảnh

            ], [
                // Custom error messages for missing required fields
                'name.required' => 'Please fill in the name',
                'description.required' => 'Please fill in the description',
                'goal_amount.required' => 'Please fill in the goal amount',
                // 'collected_amount.required' => 'Please fill in the collected amount',
                // 'status.required' => 'Please fill in the status',
                // 'start_date.required' => 'Please fill in the start date',
                'end_date.required' => 'Please fill in the end date',
                'end_date.after:start_date' => 'Please choose the end date after the start date',
                'cate_id.required' => 'Please fill in the category id',
            ]);

            // Get all the request data
            $projectData = $request->except('image');
            $projectData['create_by'] = $currentUser->id;
            $projectData['start_date'] = Carbon::now();
            $projectData['collected_amount'] = 0;
            $projectData['status'] = "Pending";

            // $projectData['date'] = Carbon::now(); // Add the current date to 'date'
            if ($projectData['start_date'] > $projectData['end_date']) {
                return response()->json([
                    'error' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
                ], 422);
            }
            // Create a new project using the validated data
            $project = Project::create($projectData);
            // Return a successful response with the created project data
            // Xử lý ảnh và lưu vào bảng images
            if ($request->has('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;

                $path = 'uploads/image/';
                $file->move($path, $filename);
                Image::create([
                    'link' => $path . $filename,
                    'project_id' => $project->id,
                ]);

            }
            return response()->json([
                'message' => 'Project created successfully',
                'data' => $project,
            ], 201);

        } catch (\Exception $e) {
            // Catch any exceptions and return an error response
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getEditProject($id)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $request = Project::where("projects.id", $id)
                ->join('users', 'projects.create_by', '=', 'users.id')
                ->join('images', 'projects.id', '=', 'images.project_id')
                ->select('projects.*', 'users.username as Creator', 'images.link as LinkImage')
                ->first();

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
    // public function getEditPendingProject($id)
    // {
    //     try {
    //         // Lấy thông tin user hiện tại từ token
    //         $currentUser = auth('api')->user();
    //         // $id = auth('api')->user()->id;

    //         if (!$currentUser) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //         // Query the complaint by ID, joining with the Resident table to get the requester's name
    //         $request = PendingProject::where("pending_projects.id", $id)
    //             ->join('users', 'pending_projects.create_by', '=', 'users.id')
    //             ->join('images', 'projects.id', '=', 'images.project_id')
    //             ->select('pending_projects.*', 'users.username as Creator','images.link as LinkImage')
    //             ->first(); // Retrieve the first matching record

    //         // If the request exists, return it as a JSON response
    //         if ($request) {
    //             return response()->json($request);
    //         } else {
    //             // If not found, return a 404 error with a message
    //             return response()->json(['error' => 'Not found detail of project'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         // Return a 500 error with the exception message in case of failure
    //         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }
    // public function updateProjectPending(Request $request, $id)
    // {
    //     try {
    //         // Lấy thông tin user hiện tại từ token
    //         $currentUser = auth('api')->user();
    //         // $id = auth('api')->user()->id;

    //         if (!$currentUser) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //         // Validate the 'status' field from the request
    //         $validated = $request->validate([
    //             'name' => 'required',
    //             'description' => 'required',
    //             'goal_amount' => 'required',
    //             // 'collected_amount' => 'required',
    //             // 'status' => 'required',
    //             'start_date' => 'nullable|date',
    //             'end_date' => 'required|date|after:start_date',
    //             'cate_id' => 'required',

    //         ], [
    //             // Custom error messages for missing required fields
    //             'name.required' => 'Please fill in the name',
    //             'description.required' => 'Please fill in the description',
    //             'goal_amount.required' => 'Please fill in the goal amount',
    //             // 'collected_amount.required' => 'Please fill in the collected amount',
    //             // 'status.required' => 'Please fill in the status',
    //             // 'start_date.required' => 'Please fill in the start date',
    //             'end_date.required' => 'Please fill in the end date',
    //             'end_date.after:start_date' => 'Please choose the end date after the start date',
    //             'cate_id.required' => 'Please fill in the category id',
    //         ]);
    //         // Find the complaint request by its ID
    //         $project = PendingProject::find($id);
    //         if ($project) {
    //             if ($request->input('end_date') < $project['start_date']) {
    //                 return response()->json([
    //                     'error' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
    //                 ], 422);
    //             }
    //             // $status = "Pending";
    //             $project->update([
    //                 'name' => $request->input('name'),
    //                 'description' => $request->input('description'),
    //                 'goal_amount' => $request->input('goal_amount'),
    //                 // 'status' =>$status,
    //                 'end_date' => $request->input('end_date'),
    //             ]);
    //             return response()->json([
    //                 "data" => $project,
    //                 "message" => "Update successfully",
    //             ]);
    //         } else {
    //             // If not found, return a 404 error with a custom message
    //             return response()->json(['message' => 'Project not found'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         // Catch any exceptions and return a 500 error response with the exception message
    //         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }

    // }
    public function updateProject(Request $request, $id)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            // $id = auth('api')->user()->id;

            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Validate the 'status' field from the request
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'goal_amount' => 'required',
                // 'collected_amount' => 'required',
                // 'status' => 'required',
                'start_date' => 'nullable|date',
                'end_date' => 'required|date|after:start_date',
                'cate_id' => 'required',

            ], [
                // Custom error messages for missing required fields
                'name.required' => 'Please fill in the name',
                'description.required' => 'Please fill in the description',
                'goal_amount.required' => 'Please fill in the goal amount',
                // 'collected_amount.required' => 'Please fill in the collected amount',
                // 'status.required' => 'Please fill in the status',
                // 'start_date.required' => 'Please fill in the start date',
                'end_date.required' => 'Please fill in the end date',
                'end_date.after:start_date' => 'Please choose the end date after the start date',
                'cate_id.required' => 'Please fill in the category id',
            ]);
            // Find the complaint request by its ID
            $project = Project::find($id);
            if ($project) {
                if ($request->input('end_date') < $project['start_date']) {
                    return response()->json([
                        'error' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu.',
                    ], 422);
                }
                $project->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'goal_amount' => $request->input('goal_amount'),
                    // 'status' => $request->input('status'),
                    'end_date' => $request->input('end_date'),
                    'cate_id' => $request->input('cate_id'),
                    // 'status' => $request->input('status'),
                ]);
                return response()->json([
                    "data" => $project,
                    "message" => "Update status successfully",
                ]);
            } else {
                // If not found, return a 404 error with a custom message
                return response()->json(['message' => 'Project not found'], 404);
            }
        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }

    public function deleteProject(Request $request, $id)
    {
        try {
            $currentUser = auth('api')->user();
            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $project = Project::find($id);

            // Check if the project exists
            if ($project) {
                // Perform a soft delete (mark the record as deleted without actually removing it from the database)
                $project->delete();

                // Return a success response after the soft delete
                return response()->json(['message' => 'Project has been soft deleted successfully']);

            } else {
                // If the project is not found, return a 404 error with a custom message
                return response()->json(['error' => 'Project not found'], 404);
            }

        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function restoreProject($id)
    {
        try {
            $currentUser = auth('api')->user();
            if (!$currentUser) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            // Find the project by its ID, including soft-deleted records
            $project = Project::withTrashed()->find($id);

            // Check if the project exists (either deleted or active)
            if ($project) {
                // Restore the soft-deleted record
                $project->restore();

                // Return a success response after the restoration
                return response()->json(['message' => 'Project has been restored successfully']);

            } else {
                // If the Project is not found, return a 404 error with a custom message
                return response()->json(['error' => 'Project not found'], 404);
            }

        } catch (\Exception $e) {
            // Catch any exceptions and return a 500 error response with the exception message
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }
}