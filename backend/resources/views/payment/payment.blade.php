<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <!-- Bootstrap CSS (tuỳ chọn) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Form Thanh Toán</h2>
        <form method="POST" action="{{ route('payment.create') }}">
            @csrf
            <!-- Project ID -->
            <div class="mb-3">
                <label for="project_id" class="form-label">ID Dự án</label>
                <input type="number" class="form-control" id="project_id" name="project_id" placeholder="Nhập ID dự án" required>
            </div>

            <!-- Amount -->
            <div class="mb-3">
                <label for="amount" class="form-label">Số tiền ủng hộ (VND)</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Nhập số tiền" min="1000" required>
            </div>

            <!-- User ID -->
            <div class="mb-3">
                <label for="user_id" class="form-label">ID Người dùng</label>
                <input type="number" class="form-control" id="user_id" name="user_id" placeholder="Nhập ID người dùng" required>
            </div>

            <!-- Submit Button -->
            <button type="submit"  class="btn btn-primary w-100">Thanh toán</button>
        </form>
        {{-- <a href="{{ route('payment.create') }}" class="btn btn-primary">Thanh toán qua VNPay</a> --}}
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
    intent="WELCOME"
    chat-title="AI_chatbox_support"
    agent-id="19afccd8-a91f-405d-a74d-7da21e9234c7"
    language-code="vi"
    ></df-messenger>
</body>

</html>
