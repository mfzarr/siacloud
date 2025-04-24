<!DOCTYPE html>
<html lang="en">
<head>
    <title>500 Internal Server Error</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="500 Internal Server Error" />
    <meta name="keywords" content="500, error, internal server error">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- [ error-ui ] start -->
<div class="auth-wrapper error">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    <img src="assets/images/error/500.png" alt="500 Internal Server Error" class="img-fluid">
                    <h1 class="display-1">500</h1>
                    <h5 class="text-muted my-4">Internal Server Error</h5>
                    <p class="text-muted">Sorry, something went wrong on our end. Please try again later.</p>
                    <form action="{{ url()->current() }}">
                        <button class="btn waves-effect waves-light btn-primary mb-4"><i class="feather icon-refresh-ccw mr-2"></i>Reload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ error-ui ] end -->
<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
</body>
</html>
