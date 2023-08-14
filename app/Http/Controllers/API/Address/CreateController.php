<?php

namespace App\Http\Controllers\API\Address;

use App\Actions\Address\Create;
use App\Http\Controllers\Controller;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address;
use App\Models\CepAddress;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function __invoke(Request $request): AddressResource
    {
        $data = $request->validate([
            'cep' => ['required', 'string', 'max:9'],
            'number' => ['required', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $address = (new Create)->setCepAddress(
            CepAddress::findOrCreateByCep($data['cep'])
        )->setNumber($data['number'])
            ->setComplement($data['complement'] ?? null)
            ->setNotes($data['notes'] ?? null)();

        return new AddressResource($address);
    }
}
