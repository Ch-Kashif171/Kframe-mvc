<?php

namespace Core\Exception\Handlers;

use Exception;

class SimpleException extends Exception
{
    protected $shortMessage;

    /**
     * @param $shortMessage
     */
    public function __construct($shortMessage)
    {
        parent::__construct($shortMessage);
        $this->shortMessage = $shortMessage;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Exception: {$this->shortMessage}";
    }
}
