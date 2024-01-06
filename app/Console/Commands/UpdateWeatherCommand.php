<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Automation\WeatherKit\Token;
use App\Automation\WeatherKit\Client;

use App\Models\WeatherCity;
use App\Models\WeatherData;

// Log data retreived.
use Illuminate\Support\Facades\Log;

class UpdateWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update weather for cities in cities table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $token = new Token;

        $weather = new Client((string) $token);

        $cities = WeatherCity::all();

        foreach ($cities as $city) {
            $lat = $city->lat;
            $lng = $city->lng;

            $cond = $weather->getConditions($lat, $lng);

            $cCond = new WeatherData();

            $cCond->TempFahr     = $cond['tempFahr'];
            $cCond->cond         = $cond['condition'];
            $cCond->cloudcover   = $cond['cloudCover'];
            $cCond->precipChance = $cond['precipChance'];
            $cCond->humidity     = $cond['humidity'];
            $cCond->city         = $city->id;
            $cCond->TimeCollected = now();

            $cCond->save();
        }

        $this->info('Weather Conditions Updated');

    }
}
