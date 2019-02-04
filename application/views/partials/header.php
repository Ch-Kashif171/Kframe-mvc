<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>kFram</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>">

    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo url(); ?>">Kfram</a>
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
