<?php

namespace Core\Support\Alert;

/**
 *this class is written by @Kashif Sohail
 * Simple toastr class for show error messages,
 * @render() static function call in view to render the toastr
 */
class Toastr
{

    /**
     * @return string
     */
    public static function render(){
        $html = '<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>';
        if(Toastr::has('success')) {
            $toast = Toastr::get('success');
            $html .='<script>toastr.success("'.self::escapeMessage($toast).'");</script>';
        }

        if(Toastr::has('error')) {
            $toast = Toastr::get('error');
            $html .='<script>toastr.error("'.self::escapeMessage($toast).'");</script>';
        }

        if(Toastr::has('warning')) {
            $toast = Toastr::get('warning');
            $html .='<script>toastr.warning("'.self::escapeMessage($toast).'");</script>';
        }

        if(Toastr::has('info')) {
            $toast = Toastr::get('info');
            $html .='<script>toastr.info("'.self::escapeMessage($toast).'");</script>';
        }

    return $html;

    }

    /**
     * @param $type
     * @return mixed
     */
    public static function get($type){
        $toast = $_SESSION[$type] ?? null;
        unset($_SESSION[$type]);
        return $toast;
    }

    /**
     * @param $type
     * @return bool
     */
    public static function has($type){
        return isset($_SESSION[$type]);
    }

    /**
     * @param $message
     * @return bool
     */
    public static function success($message){
        $_SESSION['success'] = $message;
        return true;
    }

    /**
     * @param $message
     * @return bool
     */
    public static function error($message){
        $_SESSION['error'] = $message;
        return true;
    }

    /**
     * @param $message
     * @return bool
     */
    public static function warning($message){
        $_SESSION['warning'] = $message;
        return true;
    }

    /**
     * @param $message
     * @return bool
     */
    public static function info($message){
        $_SESSION['info'] = $message;
        return true;
    }

    /**
     * Escape and convert message to string
     * @param mixed $message
     * @return string
     */
    private static function escapeMessage($message)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
        return htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8');
    }
}