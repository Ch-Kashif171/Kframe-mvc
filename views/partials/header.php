<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kframe</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/app.css')?>">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <!-- Scripts: Bootstrap 5 (no jQuery needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo url('/'); ?>">Kframe</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo url('/home'); ?>">Home</a>
                </li>
            </ul>
            <?php if (!auth()->check()) { ?>
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item"><a href="<?php echo url('/register'); ?>" class="btn btn-signup nav-btn-mobile"><i class="fa fa-user-plus"></i> Sign Up</a></li>
                    <li class="nav-item"><a href="<?php echo url('/login'); ?>" class="btn btn-signin nav-btn-mobile"><i class="fa fa-sign-in"></i> Sign In</a></li>
                </ul>
            <?php } else { ?>
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle-o me-2" style="font-size: 1.3rem;"></i>
                            <?php echo htmlspecialchars(auth()->user()->name ?? 'User'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo url('/logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>
