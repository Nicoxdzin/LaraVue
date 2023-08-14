<?php

namespace App\Http\Controllers\API\Address;

use App\Actions\Address\Update;
use App\Models\Address;
use App\Models\CepAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Address\AddressResource;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Address $address): AddressResource
    {
        $address->load('cepAddress');

        $data = collect(
            $request->validate([
                'cep' => ['nullable', 'string', 'max:9'],
                'number' => ['nullable', 'string', 'max:10'],
                'complement' => ['nullable', 'string', 'max:50'],
                'notes' => ['nullable', 'string', 'max:255'],
            ])
        )->filter(function ($value) {
            return $value !== null;
        });

        if ($data->has('cep') && ($address->cepAddress->cep !== preg_replace('/[^0-9]/', '', $data->get('cep')))) {
            $data->put('cepAddress', CepAddress::findOrCreateByCep($data->get('cep')));
        }

        $data->forget('cep');

        return new AddressResource(
            (new Update)->setAddress($address)
                ->setCepAddress($data->get('cepAddress'))
                ->fill(
                    $data->except('cepAddress')->toArray()
                )()
        );
    }
}
