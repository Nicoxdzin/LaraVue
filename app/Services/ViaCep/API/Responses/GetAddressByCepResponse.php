<?php

namespace App\Services\ViaCep\API\Responses;

use Illuminate\Http\Client\Response;
use App\Services\ViaCep\Exceptions\InvalidCep;


class GetAddressByCepResponse
{
    public string $cep;

    public string $stateInitials;

    public string $city;

    public string $neighborhood;

    public string $street;

    public function __construct(protected Response $response)
    {
        $response = $response->json();

        if (isset($response['erro']) && $response['erro'] === true) {
            throw new InvalidCep('CEP não encontrado');
        }

        $this->cep = preg_replace('/[^0-9]/', '', $response['cep']);
        $this->stateInitials = $response['uf'];
        $this->city = $response['localidade'];
        $this->neighborhood = $response['bairro'];
        $this->street = $response['logradouro'];
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function toArray(): array
    {
        return [
            'cep' => $this->cep,
            'state_initials' => $this->stateInitials,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'street' => $this->street,
        ];
    }
}
