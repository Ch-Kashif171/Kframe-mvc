<?php

namespace Core\Support;

use Core\Support\Session;

class Redirector
{
    protected $url;
    protected $messages = [];

    /**
     * Redirect to a specific URL
     * @param string $url
     * @return $this
     */
    public function to($url)
    {
        $this->url = url($url);
        return $this;
    }

    /**
     * Redirect back to the previous page
     * @return $this
     */
    public function back()
    {
        $this->url = $_SERVER['HTTP_REFERER'] ?? url('/');
        return $this;
    }

    /**
     * Attach a flash message and redirect
     * @param string $type
     * @param string $message
     * @return void
     */
    public function with($type, $message)
    {
        Session::put($type, $message);
        $this->go();
    }

    /**
     * Redirect back with a flash message
     * @param string $type
     * @param string $message
     * @return void
     */
    public function backWith($type, $message)
    {
        $this->back();
        $this->with($type, $message);
        $this->go();
    }

    /**
     * Perform the redirect
     * @return void
     */
    public function go()
    {
        header('Location: ' . $this->url);
        exit;
    }
} 