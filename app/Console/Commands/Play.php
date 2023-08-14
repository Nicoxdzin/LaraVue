<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\GetAddressByCep;
use App\Models\CepAddress;

class Play extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just for tests purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cep = CepAddress::findOrCreateByCep('83045-170');
    }
}
