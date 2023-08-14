<?php

namespace App\Actions;

use App\Services\ViaCep\API\Http\GetAddressByCep as GetAddressByCepService;
use App\Services\ViaCep\API\Responses\GetAddressByCepResponse;

class GetAddressByCep
{
    public string $cep;

    public function __invoke(): GetAddressByCepResponse
    {
        return (new GetAddressByCepService)($this->cep);
    }

    public function setCep(string $cep): self
    {
        $this->cep = preg_replace('/[^0-9]/', '', $cep);

        return $this;
    }
}
