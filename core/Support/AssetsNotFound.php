<?php

namespace Core\Support;

/**
 * Class AssetsNotFound
 *
 * Handles 404 errors for missing static assets (css, js, images, etc.).
 *
 * @package Core\Support
 */
class AssetsNotFound
{
    /**
     * Check if the requested asset exists, and throw a 404 exception if not.
     *
     * @return bool True if asset exists or not an asset request
     * @throws \Exception If the asset is not found
     */
    public static function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        if (preg_match('/\.(css|js|png|jpg|jpeg|gif)$/', $requestUri)) {
            $file = __DIR__ . '/public' . $requestUri;
            if (!file_exists($file)) {
                header("HTTP/1.1 404 Not Found");
                throw new \Exception("404 - File not found: " . htmlspecialchars($requestUri));
            }
        }
        return true;
    }
}