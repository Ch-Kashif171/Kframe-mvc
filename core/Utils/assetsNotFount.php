<?php

$requestUri = $_SERVER['REQUEST_URI'];

if (preg_match('/\.(css|js|png|jpg|jpeg|gif)$/', $requestUri)) {
    $file = __DIR__ . '/public' . $requestUri;
    if (!file_exists($file)) {
        header("HTTP/1.1 404 Not Found");
        throw new Exception("404 - File not found: " . htmlspecialchars($requestUri));
    }
}