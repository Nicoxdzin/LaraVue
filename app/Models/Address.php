<?php

namespace App\Models;

use App\Models\CepAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    public function cepAddress(): BelongsTo
    {
        return $this->belongsTo(CepAddress::class);
    }

    public function scopeFilterByCep(Builder $query, string $cep): Builder
    {
        $cep = preg_replace('/\D/', '', $cep);

        return $query->whereHas('cepAddress', function (Builder $cepAddressQuery) use ($cep) {
            $cepAddressQuery->where('cep', "{$cep}");
        });
    }

    public function scopeFilterByStreet(Builder $query, string $streetName): Builder
    {
        return $query->whereHas('cepAddress', function (Builder $cepAddressQuery) use ($streetName) {
            $cepAddressQuery->where('street', 'like', "%{$streetName}%");
        });
    }

    public function scopeFilterByNumber(Builder $query, string $number): Builder
    {
        return $query->where('number', "{$number}");
    }
}
