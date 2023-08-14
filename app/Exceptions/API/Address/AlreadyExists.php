<?php

namespace App\Exceptions\API\Address;

use Exception;

class AlreadyExists extends Exception
{
    public function __construct()
    {
        parent::__construct('Address already exists', 409);
    }
}
