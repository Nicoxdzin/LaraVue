<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CepAddress\CepAddressResource;
use App\Models\CepAddress;
use App\Services\ViaCep\Exceptions\InvalidCep;
use Illuminate\Validation\ValidationException;

class IndexCepAddressController extends Controller
{
    public function __invoke(string $cep): CepAddressResource
    {
        try {
            $address = CepAddress::findOrCreateByCep($cep);
        } catch (InvalidCep $e) {
            throw ValidationException::withMessages([
                'cep' => 'O CEP informado é inválido',
            ]);
        }

        return new CepAddressResource($address);
    }
}
