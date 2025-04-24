<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Perusahaan - Proyek SIA</title>
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
    <!-- [ create-perusahaan ] start -->
    <div class="auth-wrapper align-items-stretch aut-bg-img">
        <div class="flex-grow-1">
            <div class="h-100 d-md-flex align-items-center auth-side-img">
                <div class="col-sm-10 auth-content w-auto">
                    <img src="assets/images/logosia.png" alt="" class="img-fluid" style="max-width: 200px;">
                    <h1 class="text-white my-4">Create Your Company!</h1>
                    <h4 class="text-white font-weight-normal">
                        Lengkapi informasi berikut untuk membuat profil perusahaan dagang Anda.
                    </h4>
                </div>
            </div>
            <div class="auth-side-form">
                <div class="auth-content">
                    <img src="assets/images/auth/auth-logo-dark.png" alt="" class="img-fluid mb-4 d-block d-xl-none d-lg-none">
                    <h3 class="mb-4 f-w-400">Create Perusahaan</h3>
                    <form method="POST" action="{{ route('create.perusahaan') }}">
                        @csrf <!-- CSRF Protection -->

                        <!-- Nama Perusahaan -->
                        <div class="form-group mb-3">
                            <label class="" for="nama">Nama Perusahaan</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="" required>
                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Alamat Perusahaan -->
                        <div class="form-group mb-3">
                            <label class="" for="alamat">Alamat Perusahaan</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="" required>
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Jenis Perusahaan (Invisible, auto-selected) -->
                        <input type="hidden" id="jenis_perusahaan" name="jenis_perusahaan" value="dagang">

                        <!-- Kode Perusahaan (Auto-generated, hidden) -->
                        <input type="hidden" name="kode_perusahaan" id="kode_perusahaan">

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Create Perusahaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ create-perusahaan ] end -->

    <!-- Required Js -->
    {{-- <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootsscriptin.js"></script>
    <script src="assets/js/ripple.js"></script>
    <script src="assets/js/pcoded.min.js"></script> --}}
</body>
</html>
