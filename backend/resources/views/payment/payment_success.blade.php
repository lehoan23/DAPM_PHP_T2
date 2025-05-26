<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Thành Công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }
        .success-header {
            text-align: center;
            color: #28a745;
        }
        .success-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .success-header p {
            font-size: 16px;
            color: #555;
        }
        .transaction-details {
            margin-top: 20px;
        }
        .transaction-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .transaction-details th, 
        .transaction-details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .transaction-details th {
            background-color: #f8f9fa;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-header">
            <h1>Thanh Toán Thành Công!</h1>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
        </div>
        <div class="transaction-details">
            <h3>Chi Tiết Giao Dịch</h3>
            <table>
                <tr>
                    <th>Mã Giao Dịch</th>
                    <td>{{ $inputData['vnp_TxnRef'] }}</td>
                </tr>
                <tr>
                    <th>Số Tiền</th>
                    <td>{{ number_format($inputData['vnp_Amount'] / 100) }} VND</td>
                </tr>
                <tr>
                    <th>Thời Gian</th>
                    <td>{{ date('d-m-Y H:i:s', strtotime($inputData['vnp_PayDate'])) }}</td>
                </tr>
                <tr>
                    <th>Ngân Hàng</th>
                    <td>{{ $inputData['vnp_BankCode'] }}</td>
                </tr>
                <tr>
                    <th>Nội Dung</th>
                    <td>{{ $inputData['vnp_OrderInfo'] }}</td>
                </tr>
            </table>
        </div>
        <div class="btn-container">
            <a href="/" class="btn">Quay Lại Trang Chủ</a>
        </div>
    </div>
</body>
</html>
