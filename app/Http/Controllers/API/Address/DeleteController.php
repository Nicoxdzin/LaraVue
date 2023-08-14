<?php

namespace App\Http\Controllers\API\Address;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Address $address)
    {
        $address->delete();

        return response()->noContent();
    }
}
