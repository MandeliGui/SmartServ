<?php

namespace App\Console\Commands;

use App\Services\ExternalApi\Asaas\Facades\Asaas;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;

class Playground extends Command
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
    protected $description = 'Playground de testes';

    /**
     * Execute the console command.
     * @throws ConnectionException
     */
    public function handle()
    {
        if (\Config::get('app.env') == 'local') {


            /** @var Customer $customer */
            $customer = Asaas::customers()->get();
            dd($customer);


        } else {
            $this->error('Comando proibido fora do ambiente local');
        }
    }
}
