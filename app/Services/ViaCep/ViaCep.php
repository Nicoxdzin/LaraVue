<?php

namespace App\Services\ViaCep;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ViaCep
{
    protected array $config;

    protected PendingRequest $request;

    public function __construct()
    {
        $this->config = config('services.viacep');

        $this->generateRequest();
    }

    private function generateRequest()
    {
        $this->request = Http::asJson()
            ->acceptJson()
            ->withoutVerifying()
            ->baseUrl($this->config['base_url']);
    }
}
