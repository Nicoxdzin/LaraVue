<?php

namespace App\Services\ViaCep\Exceptions;

use Exception;

class InvalidCep extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
