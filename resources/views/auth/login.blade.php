<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Proyek SIA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="assets/images/logosia.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- [ signin-img ] start -->
    <div class="auth-wrapper align-items-stretch aut-bg-img">
        <div class="flex-grow-1">
            <div class="h-100 d-md-flex align-items-center auth-side-img">
                <div class="col-sm-10 auth-content w-auto">
                    <img src="assets/images/logosia.png" alt="" class="img-fluid" style="max-width: 200px;">
                    <h1 class="text-white my-4">Welcome Back!</h1>
                    <h4 class="text-white font-weight-normal">
                        Selamat datang di Aplikasi Perusahaan Dagang<br>Silahkan login untuk mengakses fitur fitur pada aplikasi
                    </h4>
                </div>
            </div>
            <div class="auth-side-form">
                <div class="auth-content">
                    <img src="assets/images/auth/auth-logo-dark.png" alt="" class="img-fluid mb-4 d-block d-xl-none d-lg-none">
                    <h3 class="mb-4 f-w-400">Signin</h3>
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf
                    
                        <!-- Email or Username -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="login">Email address or Username</label>
                            <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required autofocus>
                            @if($errors->has('login'))
                            <span class="text-danger">{{ $errors->first('login') }}</span>
                            @endif
                        </div>
                    
                        <!-- Password -->
                        <div class="form-group mb-4 position-relative">
                            <label for="Password" class="floating-label">Password</label>
                            <input type="password" class="form-control" id="Password" name="password" required style="padding-right: 2.5rem;">
                            <span class="position-absolute" onclick="togglePasswordVisibility()" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </span>
                            @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    
                        <div class="custom-control custom-checkbox text-left mb-4 mt-2">
                            <input type="checkbox" class="custom-control-input" id="rememberCredentials" name="remember">
                            <label class="custom-control-label" for="rememberCredentials">Remember Me</label>
                        </div>
                    
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-block btn-primary mb-4">Masuk</button>
                    
                        <p class="mb-2 text-muted">Lupa password? <a href="{{ route('password.request') }}" class="f-w-400">Reset</a></p>
                        <p class="mb-2 text-muted">Belum punya akun? <a href="{{ route('register') }}" class="f-w-400">Signup</a></p>

                        <!-- OR Divider -->
                        <div class="divider">OR</div>

                        <!-- Google Login Button -->
                        <a href="auth/redirect" class="google-btn mb-4">
                            <i class="fab fa-google"></i> Continue with Google
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ signin-img ] end -->

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/ripple.js"></script>
    <script src="assets/js/pcoded.min.js"></script>

    <!-- Client-Side Validation Script -->
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("Password");
            var passwordIcon = document.getElementById("password-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            var password = document.getElementById('Password').value;
    
            // Check if the password meets the length requirement
            if (password.length < 6) {
                event.preventDefault(); // Stop form submission
                alert("Password must be at least 6 characters long.");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('Password');
            const rememberCheckbox = document.getElementById('rememberCredentials');

            // Check if there are saved credentials
            if (localStorage.getItem('rememberedLogin')) {
                loginInput.value = localStorage.getItem('rememberedLogin');
                rememberCheckbox.checked = true;
            }

            // Save credentials when form is submitted
            document.getElementById('loginForm').addEventListener('submit', function() {
                if (rememberCheckbox.checked) {
                    localStorage.setItem('rememberedLogin', loginInput.value);
                } else {
                    localStorage.removeItem('rememberedLogin');
                }
            });
        });
    </script>
</body>

</html>