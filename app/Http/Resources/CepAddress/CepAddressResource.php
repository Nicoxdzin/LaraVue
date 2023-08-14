<?php

namespace App\Http\Resources\CepAddress;

use App\Models\CepAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CepAddress $resource
 */
class CepAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cepAddress = $this->resource;

        return [
            'id' => $cepAddress->id,
            'cep' => $cepAddress->cep,
            'state_initials' => $cepAddress->state_initials,
            'city' => $cepAddress->city,
            'neighborhood' => $cepAddress->neighborhood,
            'street' => $cepAddress->street,
        ];
    }
}
