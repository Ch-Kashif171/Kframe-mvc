<?php

namespace Core\Support;

use Core\Support\Traits\Csrf\CsrfToken;

class Form
{
    use CsrfToken;

    /**
     * @param $type
     * @return string
     */
    public static function method($type): string
    {
        return match ($type) {
            $type == 'DELETE', $type == 'PATCH', $type == 'PUT' => $type,
            default => 'PUT',
        };
    }
}
