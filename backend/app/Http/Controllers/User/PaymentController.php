<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    //
    //lấy ra danh sách các project đã đăng kí (những dự án mà mình đã payment )
    public function getRegisteredProject(Request $request)
    {
        try {
            // Lấy thông tin user hiện tại từ token
            $currentUser = auth('api')->user();
            $idUser = auth('api')->user()->id;
            // Get the limit for pagination from the query string, default to 10 if not provided
            $limit = $request->input('limit', 10);

            // Query the database for complaints, joining with the Resident table to get requester names
            $listProject = Payment::where("payments.user_id", "=", $idUser)
                ->join('projects', 'payments.project_id', '=', 'projects.id')
                ->select('payments.*', 'projects.name as ProjectName',
                    'projects.description as ProjectDescription',
                    'projects.goal_amount as ProjectGoalAmount',
                    'projects.collected_amount as ProjectCollectedAmount',
                    'projects.status as ProjectStatus',
                    'projects.start_date as ProjectStartDate',
                    'projects.end_date as ProjectEndDate',
                )

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

    public function createPayment(Request $request){

    }

}