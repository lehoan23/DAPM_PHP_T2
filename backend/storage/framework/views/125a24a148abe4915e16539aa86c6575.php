<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified Successfully</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f3f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }
        .container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #002559;
        }
        .container p {
            font-size: 16px;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #002559;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0041a8;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Email Verified Successfully!</h1>
        <p>Thank you for verifying your email. Your account is now active, and you can log in to start using our services.</p>
        <a href="../../../../frontend/login.html" class="btn">Go to Login</a>
        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> TechStore. All rights reserved.
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\PHP_CNM\DAPM_PHP_T2\backend\resources\views/mail/verify-success.blade.php ENDPATH**/ ?>