<?php

namespace Core\Support;

class Request
{
    private $field = array();


    public function post($key)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : null;
        }

        throw new \ErrorException("You are getting input value with post method, while your request is get.");
    }

    public function get($key)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : null;
        }

        throw new \ErrorException("You are getting input value with get method, while your request is post");
    }

    public function input($key)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : null;
        }
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : null;
    }

    public function all()
    {
        $file = [];
        $fields = [];
        if (isset($_FILES)) {
            $file = $this->getFilesName();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $fields = $this->sanitize($_GET);
        } else {
            $fields = $this->sanitize($_POST);
        }

        return array_merge($fields,$file);
    }

    public function getFile($file_name)
    {
        if (isset($_FILES)) {
            return $_FILES[$file_name];
        } else {
            return false;
        }
    }

    public function getFiles()
    {
        if (isset($_FILES)) {
            return $_FILES;
        } else {
            return false;
        }
    }

    public function hasFile($key)
    {
        if (isset($_FILES[$key]) && $_FILES[$key]['name'] != '') {
            return true;
        } else {
            return false;
        }
    }

    public function has($key)
    {
        $field = false;

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_FILES[$key])) {
            if (isset($_GET[$key])) {
                $field = true;
            } else {
                $field = false;
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES[$key])) {
            if (isset($_POST[$key])) {
                $field = true;
            } else {
                $field = false;
            }
        } else if (isset($_FILES[$key])) {
            $field = true;
        } else {
            $field = false;
        }

        return $field;
    }

    private function getFilesName()
    {

        $names = [];
        $files = $_FILES;
        foreach ($files as $name => $file) {
            $names[$name] = $file['name'];
        }

        return $names;
    }

    public function except()
    {
        $args = func_get_args();
        $inputs = $this->sanitize($_POST);
        foreach ($args as $value){
            unset($inputs[$value]);
        }
        return $inputs;
    }

    public function only()
    {
        $args = func_get_args();
        $inputs = $this->sanitize($_POST);
        $values = [];
        foreach ($args as $value){
            $values[$value] = isset($inputs[$value]) ? $inputs[$value] : null;
        }
        return $values;
    }

    public function session()
    {
        return new Session();
    }

    public function __get($name)
    {
        $this->field = $this->all();
        if(isset($this->field[$name])) {
            return $this->field[$name];
        } else {
            throw new \Exception("$name does not exists");
        }
    }

    /**
     * Sanitize a value or array of values.
     */
    private function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate an uploaded file (basic checks: type, size, error).
     * @param array $file The file array from $_FILES
     * @param array $allowedTypes List of allowed mime types
     * @param int $maxSize Maximum allowed size in bytes
     * @return bool|string True if valid, error message if not
     */
    public function validateFile($file, $allowedTypes = ['image/jpeg','image/png','application/pdf'], $maxSize = 2097152)
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return 'Invalid file parameters.';
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'File upload error.';
        }
        if ($file['size'] > $maxSize) {
            return 'File size exceeds limit.';
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            return 'Invalid file type.';
        }
        return true;
    }
}