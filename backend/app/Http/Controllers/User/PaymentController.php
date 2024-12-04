<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Project;
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
                ->join('users as u1', 'payments.user_id', '=', 'u1.id') // Join để lấy Creator
                ->join('users as u2', 'projects.create_by', '=', 'u2.id') // Join để lấy IdToChuc
                ->select(
                    'payments.*',
                    'projects.name as ProjectName',
                    'projects.description as ProjectDescription',
                    'projects.goal_amount as ProjectGoalAmount',
                    'projects.collected_amount as ProjectCollectedAmount',
                    'projects.status as ProjectStatus',
                    'projects.start_date as ProjectStartDate',
                    'projects.end_date as ProjectEndDate',
                    'u1.username as Creator', // Creator từ payments.user_id
                    'u2.username as ToChucName'  // Tên tổ chức từ projects.create_by
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

    public function createPayment(Request $request)
    {

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'project_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'user_id' => ['required', 'integer'],
        ]);

        session([
            'payment_data' => [
                'project_id' => $validated['project_id'],
                'amount' => $validated['amount'],
                'user_id' => $validated['user_id'],
            ]
        ]);

        session()->save();

        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = "http://127.0.0.1:8000/checkout"; //config('vnpay.vnp_Returnurl')
        $vnp_TmnCode = config('vnpay.vnp_TmnCode'); //Mã website tại VNPAY 
        $vnp_HashSecret = config('vnpay.vnp_HashSecret'); //Chuỗi bí mật

        $vnp_TxnRef = rand(1, 10000); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toan giao dich";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $validated['amount'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }


        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json([
            'status' => 'success',
            'payment_url' => $vnp_Url,
            'session' => session('payment_data')

        ]);
    }

    public function checkoutPayment(Request $request)
    {
        $paymentData = session('payment_data');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.vnp_HashSecret'));

        if ($secureHash === $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                // Thanh toán thành công
                $project_id = $paymentData['project_id'];
                $user_id = $paymentData['user_id'];

                // Lấy thông tin dự án
                $project = Project::findOrFail($project_id);

                // Lấy thông tin người dùng (người ủng hộ)
                $user = User::findOrFail($user_id);

                $payment = new Payment();
                $payment->amount = $paymentData['amount']; // Số tiền thanh toán
                $payment->payment_method = 'VNPay';
                $payment->status = 'Success';
                $payment->user_id = $user->id;
                $payment->project_id = $project->id;
                $payment->save();

                // Cập nhật số tiền đã thu
                // $project->collected_amount += $request->vnp_Amount;
                // if ($project->collected_amount >= $project->goal_amount) {
                //     $project->status = 'Completed';
                // }
                // Cập nhật số tiền đã thu
                $project->collected_amount = $project->collected_amount + $payment->amount;
                if ($project->collected_amount >= $project->goal_amount) {
                    $project->status = 'Completed';
                }
                $project->save();

                $queryData = http_build_query([
                    'status' => 'success',
                    'vnp_TxnRef' => $inputData['vnp_TxnRef'],
                    'vnp_Amount' => $inputData['vnp_Amount'],
                    'vnp_PayDate' => $inputData['vnp_PayDate'],
                    'vnp_BankCode' => $inputData['vnp_BankCode'],
                    'vnp_OrderInfo' => $inputData['vnp_OrderInfo'],
                ]);

                return redirect()->to('http://127.0.0.1:5502/payment_success.html?' . $queryData);
            } else {
                // Thanh toán không thành công
                return redirect()->to('http://127.0.0.1:5502/payment_failed.html');
            }
        } else {
            // Dữ liệu không hợp lệ
            return redirect()->to('http://127.0.0.1:5502/payment_failed.html');
        }
    }
}
