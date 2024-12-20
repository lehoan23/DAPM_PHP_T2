<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="./style.css"> --}}
</head>
<body>
    <div class="container">
        <form class="form" id="login">
            <h1 class="form__title">Đăng nhập</h1>
            <div class="form__message form__message--error"></div>
            <div class="form__input-group">
                <input type="text" class="form__input" autofocus placeholder="Email">
                <div class="form__input-error-message"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" autofocus placeholder="Mật khẩu">
                <div class="form__input-error-message"></div>
            </div>
            <p class="form__textForgot">
                <a href="./forgot-password.html" class="form__linkForgot">Quên mật khẩu?</a>
            </p>
            <button class="form__button" type="submit">Đăng nhập</button>
            <div class="form__social-login">
                <p class="form__text">Hoặc đăng nhập bằng:</p>
                <a href="{{ route('auth.google') }}" class="form__social-icon">
                    <!-- Google Logo -->
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/768px-Google_%22G%22_logo.svg.png" alt="Google Logo" class="form__icon-img">
                </a>
                <a href="#" class="form__social-icon">
                    <!-- Facebook Logo -->
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Facebook Logo" class="form__icon-img">
                </a>
            </div>
            
            <p class="form__text">
                Không có tài khoản? <a href="./signup.html" class="form__link" id="linkCreateAccount">Tạo tài khoản</a>
            </p>

        </form>
    </div>
    {{-- <script src="./js/login.js"></script> --}}
</body>
</html>
