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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://localhost/Kframe-mvc/public/css/app.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f3f4f7, #d9e0f0);
            font-family: 'Raleway', sans-serif;
        }

        h1.welcome-title {
            font-size: 70px;
            font-weight: 800;
            text-align: center;
            color: #5e2d2dd6;
            margin-top: 150px;
            letter-spacing: 10px;
        }

        p.subtitle {
            text-align: center;
            color: gray;
            margin-bottom: 150px;
            font-size: x-large;
            font-weight: 700;
        }

        .panel {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .panel-heading {
            font-size: 22px;
            font-weight: bold;
            background-color: #5e2d2d;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .navbar-inverse {
            background-color: #2c3e50;
            border: none;
        }

        .navbar-inverse .navbar-brand,
        .navbar-inverse .navbar-nav > li > a {
            color: #ecf0f1;
        }

        .navbar-inverse .navbar-nav > li > a:hover {
            background-color: #34495e;
            color: #fff;
        }

        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 15px 0;
            text-align: center;
            margin-top: 50px;
        }

        footer a {
            color: #f39c12;
            text-decoration: none;
        }

        footer a:hover {
            color: #e67e22;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo url(); ?>">Kframe</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="<?php echo url(); ?>">Home</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <?php if(!auth()->check()) {?>
                <li><a href="<?php echo url('register'); ?>"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                <li><a href="<?php echo url('login'); ?>"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <?php }else{  ?>

                <div class="dropdown">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#"><?php echo user()->email; ?></a></li>
                    </ul>

                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><span class="caret"></span></button>

                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url(); ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>

                        <li><a href="<?php echo url('logout'); ?>"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                    </ul>

                </div>
            <?php } ?>

        </ul>

    </div>
</nav>
