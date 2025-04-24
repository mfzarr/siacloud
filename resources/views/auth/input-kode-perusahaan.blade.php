<!DOCTYPE html>
<html lang="en">

<head>
    <title>Input Kode Perusahaan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content" style="width: 750px">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3 f-w-400">Input Kode Perusahaan</h4>

                    <form method="POST" action="{{ route('handle-input-kode') }}">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label class="floating-label" for="kodePerusahaan">Kode Perusahaan</label>
                            <input type="text" class="form-control @error('kode_perusahaan') is-invalid @enderror" id="kodePerusahaan" name="kode_perusahaan" placeholder="Enter Company Code" required>
                            @error('kode_perusahaan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block mb-4">Submit</button>
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
