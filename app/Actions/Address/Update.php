<?php

namespace App\Actions\Address;

use App\Models\Address;
use App\Models\CepAddress;
use App\Traits\FillsAttributes;

class Update
{
    use FillsAttributes;

    public Address $address;

    public ?CepAddress $cepAddress;

    public ?string $number;

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
        $address = $this->address;

        $cepAddress = isset($this->cepAddress) ? $this->cepAddress : null;

        if ($cepAddress !== null && ($cepAddress->id !== $address->cep_address_id)) {
            $address->cepAddress()->associate($cepAddress);
        }

        collect($this->attributes)->filter(function ($attribute) use ($address) {
            return isset($this->{$attribute}) && ($address->{$attribute} !== $this->{$attribute});
        })->each(function ($attribute) use ($address) {
            $address->{$attribute} = $this->{$attribute};
        });


        $address->save();

        return $address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function setCepAddress(?CepAddress $cepAddress): self
    {
        $this->cepAddress = $cepAddress;

        return $this;
    }

    public function setNumber(?string $number): self
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
