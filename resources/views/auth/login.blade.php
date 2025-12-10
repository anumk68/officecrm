<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Login </title>
    <link rel="shortcut icon" href="http://localhost/crm/21novcrmserverbk/public/admin/images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #8d99ae;
            --success-color: #4caf50;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Left side with animated background */
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            color: white;
        }

        /* Animated background elements */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite linear;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
            animation-direction: reverse;
        }

        .circle-3 {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 20%;
            animation-delay: 10s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(20px, 20px) rotate(90deg);
            }

            50% {
                transform: translate(0, 40px) rotate(180deg);
            }

            75% {
                transform: translate(-20px, 20px) rotate(270deg);
            }

            100% {
                transform: translate(0, 0) rotate(360deg);
            }
        }

        /* Floating icons animation */
        .floating-icon {
            position: absolute;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.2);
            animation: floatIcon 15s infinite linear;
        }

        @keyframes floatIcon {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.2;
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.5;
            }

            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.2;
            }
        }

        .left-content {
            z-index: 2;
            position: relative;
            max-width: 600px;
        }

        .crm-logo {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            animation: pulse 2s infinite;
        }

        .left-content h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .left-content p {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .features {
            list-style: none;
            margin-top: 40px;
        }

        .features li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 16px;
        }

        .features i {
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        /* Right side with login form */
        .right-side {
            width: 45%;
            min-width: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: white;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--light-text);
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            color: var(--dark-text);
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            height: 52px;
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            padding-left: 50px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: #f9fafb;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
            font-size: 16px;
            z-index: 10;
            transition: color 0.3s;
        }

        .form-control:focus+.input-icon {
            color: var(--primary-color);
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-text);
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s;
            background: none;
            border: none;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--dark-text);
            font-weight: 500;
            font-size: 14px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .login-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            height: 52px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s;
        }

        .login-btn:hover .btn-shine {
            left: 100%;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--light-text);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e1e5e9;
        }

        .divider span {
            padding: 0 15px;
        }

        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .social-btn {
            flex: 1;
            height: 48px;
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-text);
            transition: all 0.3s;
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .social-btn i {
            margin-right: 8px;
            font-size: 16px;
        }

        .google-btn {
            color: #DB4437;
        }

        .microsoft-btn {
            color: #00A4EF;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: var(--light-text);
            font-size: 13px;
        }

        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Success animation */
        .success-checkmark {
            display: none;
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid var(--success-color);
        }

        .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }

        .check-icon .icon-line {
            height: 5px;
            background-color: var(--success-color);
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        @keyframes rotate-circle {
            0% {
                transform: rotate(-45deg);
            }

            5% {
                transform: rotate(-45deg);
            }

            12% {
                transform: rotate(-405deg);
            }

            100% {
                transform: rotate(-405deg);
            }
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }

            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }

            100% {
                width: 25px;
                left: 14px;
                top: 45px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }

            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            body {
                flex-direction: column;
            }

            .left-side {
                display: none;
            }

            .right-side {
                width: 100%;
                min-width: 100%;
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .right-side {
                padding: 15px;
            }

            .social-login {
                flex-direction: column;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        .error-border {
            border: 1px solid red !important;
        }

        .error-text {
            position: absolute;
            bottom: -20px;
        }

        /* Smooth zoom-in-out background animation */
        .left-side {
            animation: bgZoom 12s infinite alternate ease-in-out;
        }

        @keyframes bgZoom {
            0% {
                background-size: 100% 100%;
            }

            100% {
                background-size: 105% 105%;
            }
        }

        /* Left content fade + slide animation */
        .left-content {
            opacity: 0;
            animation: fadeSlideUp 1.5s ease-out forwards;
        }

        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* CRM logo bounce */
        .crm-logo .logo-icon {
            animation: logoBounce 2.2s infinite ease-in-out;
        }

        @keyframes logoBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        /* Features stagger animation */
        .features li {
            opacity: 0;
            transform: translateX(-20px);
            animation: featureFade 0.8s forwards;
        }

        .features li:nth-child(1) {
            animation-delay: 0.6s;
        }

        .features li:nth-child(2) {
            animation-delay: 0.8s;
        }

        .features li:nth-child(3) {
            animation-delay: 1.0s;
        }

        .features li:nth-child(4) {
            animation-delay: 1.2s;
        }

        .features li:nth-child(5) {
            animation-delay: 1.4s;
        }

        @keyframes featureFade {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Extra glowing pulse on floating icons */
        .floating-icon i {
            animation: glowPulse 3s infinite ease-in-out;
        }

        @keyframes glowPulse {
            0% {
                text-shadow: 0 0 4px rgba(255, 255, 255, 0.2);
            }

            50% {
                text-shadow: 0 0 12px rgba(255, 255, 255, 0.9);
            }

            100% {
                text-shadow: 0 0 4px rgba(255, 255, 255, 0.2);
            }
        }
    </style>
</head>

<body>
    <!-- Left side with animated background -->
    <div class="left-side">
        <!-- Animated background elements -->
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
        <div class="bg-circle circle-3"></div>

        <!-- Floating icons -->
        <div class="floating-icon" style="top: 20%; left: 10%; animation-delay: 0s;"><i class="fas fa-chart-line"></i>
        </div>
        <div class="floating-icon" style="top: 70%; left: 15%; animation-delay: 2s;"><i class="fas fa-users"></i></div>
        <div class="floating-icon" style="top: 40%; left: 80%; animation-delay: 4s;"><i class="fas fa-database"></i>
        </div>
        <div class="floating-icon" style="top: 80%; left: 75%; animation-delay: 6s;"><i class="fas fa-cog"></i></div>
        <div class="floating-icon" style="top: 15%; left: 70%; animation-delay: 8s;"><i class="fas fa-bell"></i></div>
        <div class="floating-icon" style="top: 50%; left: 50%; animation-delay: 1s;">
            <i class="fas fa-cloud"></i>
        </div>
        <div class="floating-icon" style="top: 30%; left: 85%; animation-delay: 3s;">
            <i class="fas fa-star"></i>
        </div>
        <div class="floating-icon" style="top: 85%; left: 25%; animation-delay: 5s;">
            <i class="fas fa-rocket"></i>
        </div>

        <!-- Left side content -->
        <div class="left-content">
            <div class="crm-logo">
                <div class="logo-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                CRM
            </div>

            <h1>Smart CRM for Growing Businesses</h1>
            <p>
                Streamline your workflow, monitor team performance, and manage customer relationships effortlessly
                with our powerful and intuitive CRM platform.
            </p>

            <ul class="features">
                <li><i class="fas fa-check"></i> Track leads, follow-ups & conversions</li>
                <li><i class="fas fa-check"></i> Visual dashboards & analytics</li>
                <li><i class="fas fa-check"></i> Automated tasks & reminders</li>
                <li><i class="fas fa-check"></i> Role-based secure access</li>

            </ul>
        </div>

    </div>

    <!-- Right side with login form -->
    <div class="right-side">
        <div class="login-container">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Login to continue to your CRM Dashboard</p>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ Cookie::get('remember_email') ?? old('email') }}"
                            placeholder="Enter your work email">

                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>


                    </div>
                </div>

                <!-- Password field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password"
                            value="{{ Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '' }}"
                            placeholder="Enter your password">

                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>


                    </div>
                </div>

                <!-- Remember me -->
                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe"
                            {{ Cookie::get('remember_checked') ? 'checked' : '' }}>


                        <label class="form-check-label" for="rememberMe">
                            Remember this device
                        </label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn" id="loginButton">
                    <span class="btn-shine"></span>
                    Sign In
                </button>

                {{-- <!-- Laravel Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
            </form>


            <div class="footer-text">
                Don't have an account? <a href="#">Contact your administrator</a><br>
                &copy; 2023 CRM Pro. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 4000,
        };

        @if (Session::has('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            const passwordIcon = passwordToggle.querySelector('i');

            // 🔹 Password Toggle
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                passwordIcon.classList.toggle('fa-eye');
                passwordIcon.classList.toggle('fa-eye-slash');
            });

            // 🔹 Form Submit
            loginForm.addEventListener('submit', function(e) {
                let hasError = false;

                // Reset all previous errors
                document.querySelectorAll('.error-text').forEach(el => el.remove());
                document.querySelectorAll('.form-control').forEach(el => el.classList.remove(
                    'error-border'));

                const email = document.getElementById('email');
                const password = document.getElementById('password');

                // Validate Email
                if (email.value.trim() === "") {
                    showError(email, "Email is required");
                    hasError = true;
                }

                // Validate Password
                if (password.value.trim() === "") {
                    showError(password, "Password is required");
                    hasError = true;
                }

                if (hasError) {
                    shakeForm();
                    e.preventDefault(); // Stop form submit
                    return;
                }
            });

            // 🔹 Show error under field
            function showError(input, message) {
                input.classList.add('error-border');
                const error = document.createElement('small');
                error.classList.add('error-text');
                error.style.color = "red";
                error.style.fontSize = "13px";
                error.innerText = message;
                input.parentElement.appendChild(error);
            }

            // 🔹 Shake animation
            function shakeForm() {
                loginForm.style.transform = 'translateX(0)';
                let shakeCount = 0;

                const shakeInterval = setInterval(() => {
                    const offset = (shakeCount % 2 === 0) ? -10 : 10;
                    loginForm.style.transform = `translateX(${offset}px)`;

                    shakeCount++;
                    if (shakeCount > 6) {
                        clearInterval(shakeInterval);
                        loginForm.style.transform = 'translateX(0)';
                    }
                }, 50);
            }

        });
    </script>

</body>

</html>
