<?php

namespace App\Console\Commands;

use App\Service\GeneralDataService;
use Illuminate\Console\Command;

class AddDataToRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-data-to-redis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        GeneralDataService::setL94DataToRedis();
        GeneralDataService::setL87DataToRedis();
        GeneralDataService::setL87_2DataToRedis();
    }
}
