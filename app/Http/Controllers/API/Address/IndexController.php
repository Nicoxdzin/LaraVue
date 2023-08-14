<?php

namespace App\Http\Controllers\API\Address;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Address\AddressResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $data = collect(
            $request->validate([
                'query' => ['nullable', 'string', 'max:255'],
            ])
        );

        $addressesQuery = Address::with([
            'cepAddress'
        ])->orderBy('created_at', 'desc');

        if ($data->has('query')) {
            $this->queryAddresses($addressesQuery, $data->get('query'));
        }

        return AddressResource::collection($addressesQuery->paginate(10));
    }

    private function queryAddresses(Builder &$addressesQuery, string $query): Builder
    {
        $queryType = preg_match('/^\d{5}-\d{3}$/', $query) || preg_match('/^\d{8}$/', $query) ? 'cep' : 'streetName';

        return match ($queryType) {
            'cep' => $addressesQuery->filterByCep($query),
            'streetName' => $this->queryByStreetName($addressesQuery, $query),
        };
    }

    private function queryByStreetName(Builder &$addressesQuery, string $query): Builder
    {
        $isStreetNameWithNumber = preg_match('/\d/', $query) === 1;

        if ($isStreetNameWithNumber) {
            $number = preg_replace('/\D/', '', $query);

            $addressesQuery->filterByNumber($number);
        }

        $query = trim(
            preg_replace('/[^\p{L}\s]/u', '', $query)
        );

        return $addressesQuery->filterByStreet($query);
    }
}
