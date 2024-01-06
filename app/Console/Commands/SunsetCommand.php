<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Automation\Hue\Connect;
use App\Automation\Wemo\Wemo;

use App\Models\Light;
use App\Models\Outlet;
use App\Models\AutomationSetting;

// Log data retreived.
use Illuminate\Support\Facades\Log;

class SunsetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:sunset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn lights on at Sunset';

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
        $lightsOnTime = $json->lotime;

        $susetObj = Carbon::createFromTimestamp($lightsOnTime);

        $timeNow = Carbon::now()->timezone('America/Chicago');

        if (($susetObj->minute == $timeNow->minute) && ($susetObj->hour == $timeNow->hour))
        {

            Log::channel('commands')->info("Lights turned on at {$timeNow}");

            $hue = new Connect;
            $wemo = new Wemo;
            $lights = Light::where('night',1)->get();
            $outlets = Outlet::where('night',1)->get();

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

            foreach ($outlets as $outlet) {
                $wemo->toggle($outlet->id, 2);
            }
        }

        $this->info('Lights turned on for Nighttime');
    }
}
