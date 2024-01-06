<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Automation\Hue\Connect;

use App\Models\WeatherData;
use App\Models\Light;
use App\Models\AutomationSetting;

class CloudyWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:cloudylights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn on the Lights if it is low light outside.';

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

        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);

        $hue = new Connect;

        if ($json->cloudy) {

            $cloudCover = (float)WeatherData::latest()->first()->cloudcover;

            if ($cloudCover > 0.70) {

                $lights = Light::where('cloudy',1)->get();

                foreach ($lights as $light) {
                    switch ($light->kind) {
                        case "xy":
                            $c = explode(" ", $light->color);
                            $color[] = (float)$c[0];
                            $color[] = (float)$c[1];
                            $hue->set_colorxy($light->lightid, $color, (int)$light->brightness);
                        break;
                        case "ct":
                            $hue->set_colorct($light->lightid, (int)$light->mired, (int)$light->brightness);
                        break;
                        case "dw":
                            $hue->setbrightness($light->lightid, (int)$light->brightness);
                    }
                }

                $this->info('It is low light inside. Lights turned on.');

            } else {

                $this->info('It is not low light inside.');

            }
        }
    }
}
