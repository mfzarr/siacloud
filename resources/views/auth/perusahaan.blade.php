<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Perusahaan - Proyek SIA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="assets/images/logosia.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content" style="width: 750px">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 f-w-400">Create Perusahaan</h4>
                    <p class="mb-4">Lengkapi informasi berikut untuk membuat profil perusahaan dagang Anda.</p>

                    <form method="POST" action="{{ route('create.perusahaan') }}">
                        @csrf

                        <!-- Nama Perusahaan -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="nama">Nama Perusahaan</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="PT D'FAM Indonesia Tbk." required>
                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Alamat Perusahaan -->
                        <div class="form-group mb-3">
                            <label class="floating-label" for="alamat">Alamat Perusahaan</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Jl. Menuju Kesuksesan" required>
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" id="jenis_perusahaan" name="jenis_perusahaan" value="dagang">
                        <input type="hidden" name="kode_perusahaan" id="kode_perusahaan">

                        <button class="btn btn-primary btn-block mb-4">Create Perusahaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/ripple.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
</body>

</html>