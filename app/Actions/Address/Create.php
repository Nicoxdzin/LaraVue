<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\CepAddress;
use App\Exceptions\API\Address\AlreadyExists;
use App\Traits\FillsAttributes;

class Create
{
    use FillsAttributes;

    public CepAddress $cepAddress;

    public string $number;

    public ?string $complement;

    public ?string $notes;

    protected $attributes = [
        'cepAddress',
        'number',
        'complement',
        'notes',
    ];

    public function __invoke(): Address
    {
        throw_if(
            Address::where('cep_address_id', $this->cepAddress->id)->where('number', $this->number)->exists(),
            new AlreadyExists
        );

        $address = new Address;

        $address->cepAddress()
            ->associate($this->cepAddress);

        $address->number = $this->number;
        $address->complement = $this->complement;
        $address->notes = $this->notes;

        $address->save();

        return $address;
    }

    public function setCepAddress(CepAddress $cepAddress): self
    {
        $this->cepAddress = $cepAddress;

        return $this;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
