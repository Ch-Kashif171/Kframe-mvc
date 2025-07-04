<?php

namespace Core\Support;

class General
{
    public static function setOldData()
    {
        // Flash old input values before controller logic
        if (!empty($_POST)) {
            Session::flash('old', $_POST);
        }
    }

    public static function clearOldData()
    {
        if (Session::has('old')) {
            Session::forget('old');
        }
    }
}