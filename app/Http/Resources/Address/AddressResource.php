<?php

namespace App\Http\Resources\Address;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Address $resource
 */
class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = $this->resource;

        return [
            'id' => $address->id,
            'cep' => $address->cepAddress->cep,
            'state_initials' => $address->cepAddress->state_initials,
            'city' => $address->cepAddress->city,
            'neighborhood' => $address->cepAddress->neighborhood,
            'street' => $address->cepAddress->street,
            'number' => $address->number,
            'complement' => $address->complement,
            'notes' => $address->notes,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}
