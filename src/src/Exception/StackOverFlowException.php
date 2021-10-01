<?php

namespace App\Exception;

use Exception;

class StackOverFlowException extends Exception
{
    public static function badStatusCode(): self
    {
        return new self('Http status code not valid!');
    }
}