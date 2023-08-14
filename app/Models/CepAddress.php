<?php

namespace App\Models;

use App\Models\Address;
use App\Actions\GetAddressByCep;
use Illuminate\Database\Eloquent\Model;

class CepAddress extends Model
{
    /**
     * @throws \App\Services\ViaCep\Exceptions\InvalidCep
     */
    public static function findOrCreateByCep(string $cep): self
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        $address = self::query()->where('cep', $cep)->first();

        if ($address === null) {
            $address = new self();

            $address->forceFill(
                (new GetAddressByCep)->setCep($cep)()->toArray()
            );

            $address->save();
        }

        return $address;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
