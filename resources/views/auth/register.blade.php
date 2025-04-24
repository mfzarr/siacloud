<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register Proyek SIA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <link rel="icon" href="assets/images/logosia.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- [ register-img ] start -->
    <div class="auth-wrapper align-items-stretch aut-bg-img">
        <div class="flex-grow-1">
            <div class="h-100 d-md-flex align-items-center auth-side-img">
                <div class="col-sm-10 auth-content w-auto">
                    <img src="assets/images/logosia.png" alt="" class="img-fluid" style="max-width: 200px;">
                    <h1 class="text-white my-4">Join Us!</h1>
                    <h4 class="text-white font-weight-normal">
                        Bergabung dengan Aplikasi Perusahaan Dagang<br>Silakan daftar untuk membuat akun Anda
                    </h4>
                </div>
            </div>
            <div class="auth-side-form">
                <div class="auth-content">
                    <img src="assets/images/auth/auth-logo-dark.png" alt="" class="img-fluid mb-4 d-block d-xl-none d-lg-none">
                    <h3 class="mb-4 f-w-400">Signup</h3>
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf <!-- CSRF Protection -->

                        <!-- name -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="name">Username</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="Email">Email address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="Email" name="email" placeholder="" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="Password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="Password" name="password" placeholder="" required minlength="6">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="PasswordConfirm">Confirm Password</label>
                            <input type="password" class="form-control" id="PasswordConfirm" name="password_confirmation" placeholder="" required>
                        </div>

                        <!-- Role Selection has been removed -->

                        <button type="submit" class="btn btn-primary btn-block mb-4">Daftar</button>
                        <p class="mb-2">Already have an account? <a href="{{ route('login') }}" class="f-w-400">Signin</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ register-img ] end -->

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/ripple.js"></script>
    <script src="assets/js/pcoded.min.js"></script>

    <!-- Client-Side Validation Script -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            var password = document.getElementById('Password').value;
            var confirmPassword = document.getElementById('PasswordConfirm').value;

            if (password !== confirmPassword) {
                event.preventDefault(); // Stop form submission
                alert("Password and Confirm Password do not match.");
            }
        });
    </script>
</body>

</html>