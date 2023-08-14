<?php

namespace App\Services\ViaCep\API\Http;

use App\Services\ViaCep\API\Responses\GetAddressByCepResponse;
use App\Services\ViaCep\ViaCep;

class GetAddressByCep extends ViaCep
{
    public function __invoke(string $cep): GetAddressByCepResponse
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        return new GetAddressByCepResponse(
            $this->request->get("{$cep}/json")
        );
    }
}
