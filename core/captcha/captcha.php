<?php
session_start();


if (isset($_POST['captcha']) && $_POST['captcha'] == "reload") {

    require_once "CaptchaBuilder.php";
    unset($_SESSION['captcha']);

    $builder = new CaptchaBuilder();
    $builder->build();

    $_SESSION['captcha'] = $builder->getPhrase();

    echo json_encode($builder->inline()); exit;

}
