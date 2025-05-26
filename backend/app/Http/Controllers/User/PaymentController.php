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

    public function paymentCreate(){
        return view('payment.payment');
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
        
        // // Lấy thông tin dự án
        // $project = Project::findOrFail($validated['project_id']);
        
        // // Lấy thông tin người dùng (người ủng hộ)
        // $user = User::findOrFail($validated['user_id']);

        // // Lấy thông tin người dùng từ bảng users, dựa trên ID người tạo dự án
        // $center = User::where('id', $project->create_by)->first();
        
        
        // if (!$center) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Không tìm thấy trung tâm (tổ chức) liên quan đến dự án này.'
        //     ], 400);
        // }

        
        
        // if ($center->id == 2) {
        //     $vnp_TmnCode = config('payment.center_1.vnp_tmn_code');
        //     $vnp_HashSecret = config('payment.center_1.vnp_hash_secret');
        //     $vnp_Url = config('payment.center_1.vnp_url');

        // } elseif ($center->id == 6) {
        //     $vnp_TmnCode = config('payment.center_2.vnp_tmn_code');
        //     $vnp_HashSecret = config('payment.center_2.vnp_hash_secret');
        //     $vnp_Url = config('payment.center_2.vnp_url');
        // } elseif ($center->id == 7) {
        //     $vnp_TmnCode = config('payment.center_3.vnp_tmn_code');
        //     $vnp_HashSecret = config('payment.center_3.vnp_hash_secret');
        //     $vnp_Url = config('payment.center_3.vnp_url');
        // } else {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Trung tâm không hợp lệ'
        //     ], 400);
        // }
        
        // // Khởi tạo thông tin thanh toán
        // $vnp_Returnurl = 'https://localhost/vnpay_php/vnpay_return.php'; // URL callback trả về từ VNPay

        // $vnp_TxnRef = '123456'; // Mã giao dịch
        // $vnp_OrderInfo = "Ủng hộ dự án: " . $project->name;
        // $vnp_OrderType = "Ung ho";
        // $vnp_Amount = $validated['amount'] * 100; // VNPay yêu cầu đơn vị là đồng
        // $vnp_Locale = "VN";
        // $vnp_BankCode = "NCB";
        // $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        // // Tạo chuỗi dữ liệu gửi VNPay
        // $inputData = [
        //     "vnp_Version" => "2.1.0",
        //     "vnp_TmnCode" => $vnp_TmnCode,
        //     "vnp_Amount" => $vnp_Amount,
        //     "vnp_Command" => "pay",
        //     "vnp_CreateDate" => date('YmdHis'),
        //     "vnp_CurrCode" => "VND",
        //     "vnp_IpAddr" => $vnp_IpAddr,
        //     "vnp_Locale" => $vnp_Locale,
        //     "vnp_OrderInfo" => $vnp_OrderInfo,
        //     "vnp_OrderType" => $vnp_OrderType,
        //     "vnp_ReturnUrl" => $vnp_Returnurl,
        //     "vnp_TxnRef" => $vnp_TxnRef,
        // ];
        
        // ksort($inputData);
        // $query = "";
        // $hashdata = "";
        // foreach ($inputData as $key => $value) {
        //     $hashdata .= $key . "=" . $value . "&";
        //     $query .= urlencode($key) . "=" . urlencode($value) . "&";
        // }
        // $vnp_Url = $vnp_Url . "?" . rtrim($query, "&");

        // if (!empty($vnp_HashSecret)) {
        //     $vnpSecureHash = hash_hmac('sha512', rtrim($hashdata, "&"), $vnp_HashSecret);
        //     $vnp_Url .= "&vnp_SecureHash=" . $vnpSecureHash;
        // }
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl ="http://127.0.0.1:8000/checkout"; //config('vnpay.vnp_Returnurl')
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');//Mã website tại VNPAY 
        $vnp_HashSecret = config('vnpay.vnp_HashSecret'); //Chuỗi bí mật
        
        $vnp_TxnRef = rand(1,10000); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
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
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
        
        // // Lưu thông tin thanh toán vào bảng `payments`
        // $payment = new Payment();
        // $payment->amount = $validated['amount'];
        // $payment->payment_method = 'VNPay'; 
        // $payment->status = 'Success';
        // $payment->user_id = $validated['user_id'];
        // $payment->project_id = $validated['project_id'];
        // $payment->save();
        
        // // Cập nhật số tiền đã thu (collected_amount) trong bảng project
        // $project->collected_amount = $project->collected_amount + $validated['amount'];  
        // if ($project->collected_amount >= $project->goal_amount) {
        //     $project->status = 'Completed'; 
        // }
        
        // $project->save();  

        // // Trả về URL thanh toán trong JSON response
        // return response()->json([
        //     'status' => 'success',
        //     'payment_url' => $vnp_Url
        // ], 200);
    }

    public function checkoutPayment(Request $request){
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
                    $payment->amount = $paymentData['amount'] /100 ; // Số tiền thanh toán
                    $payment->payment_method = 'VNPay';
                    $payment->status = 'Success';
                    $payment->user_id = $user->id;
                    $payment->project_id = $project->id;
                    $payment->save();

                    // Cập nhật số tiền đã thu
                    $project->collected_amount = $project->collected_amount + $payment->amount;
                    if ($project->collected_amount >= $project->goal_amount) {
                        $project->status = 'Completed';
                    }
                    $project->save();

                   return view('payment.payment_success', compact('inputData'));
               } else {
                   // Thanh toán không thành công
                   return view('payment.payment_failed');
               }
           } else {
               // Dữ liệu không hợp lệ
               return view('payment.payment_failed');
           }   
    }


}