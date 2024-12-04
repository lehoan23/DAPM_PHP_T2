<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Google Callback</title>

    <style>
        /* To center the spinner on the page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        /* Spinner style */
        .spinner {
            border: 4px solid #f3f3f3; /* Light gray background */
            border-top: 4px solid #3498db; /* Blue color for spinner */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite; /* Spin animation */
        }

        /* Animation for spinning */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Message text */
        h3 {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div>
        <!-- Loading spinner -->
        <div class="spinner"></div>
        <!-- Message -->
        <h3>Đang xử lý đăng nhập Google...</h3>
    </div>

    <script>
        // Lấy giá trị 'id' từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        console.log(id);

        // Gửi yêu cầu API với Fetch
        fetch(`http://127.0.0.1:8000/api/auth/createToken?id=${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                // Nếu cần, có thể thêm token CSRF
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Chuyển đổi phản hồi sang JSON
        })
        .then(data => {
            console.log('Response:', data); // Xử lý kết quả trả về từ API

            // Mở cửa sổ mới và chuyển dữ liệu
            const encodedData = encodeURIComponent(JSON.stringify(data));
            setTimeout(() => {
                window.location.href = `http://127.0.0.1:5502/index.html?userData=${encodedData}`;
            }, 3000);
            
        })
        .catch(error => {
            console.error('Error:', error); // Xử lý lỗi
        });
    </script>
</body>
</html>