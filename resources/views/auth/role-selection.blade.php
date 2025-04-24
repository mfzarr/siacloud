<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Role - SIA Cloud</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content" style="width: 750px">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 f-w-400">Pilih Role Anda</h4>
                    <p class="mb-4">Silakan pilih role yang sesuai dengan kebutuhan Anda</p>

                    <form method="POST" action="{{ route('handle-role-selection') }}">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-around">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="roleOwner" name="role" value="owner" class="custom-control-input" required>
                                    <label class="custom-control-label" for="roleOwner">
                                        <div class="text-center">
                                            <i class="feather icon-user-check mb-2" style="font-size: 2rem;"></i>
                                            <h5>Owner</h5>
                                            <p>Pemilik Perusahaan</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="rolePegawai" name="role" value="pegawai" class="custom-control-input" required>
                                    <label class="custom-control-label" for="rolePegawai">
                                        <div class="text-center">
                                            <i class="feather icon-users mb-2" style="font-size: 2rem;"></i>
                                            <h5>Pegawai</h5>
                                            <p>Karyawan Perusahaan</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('role')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block mb-4">Lanjutkan</button>
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