<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Automation\Hue\Connect;
use App\Automation\Wemo\Wemo;
use Carbon\Carbon;

use App\Models\Light;
use App\Models\Outlet;
use App\Models\AutomationSetting;

// Log data retreived.
use Illuminate\Support\Facades\Log;

class HomeAwayTurnOffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:lightsoff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn off at night when away.';

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

        $lightsOffObj = Carbon::createFromTimestamp($json->hatime);
        $timeNow = Carbon::now()->timezone('America/Chicago');

        if ($json->awaylightsoff) {
            if (($lightsOffObj->minute == $timeNow->minute) && ($lightsOffObj->hour == $timeNow->hour))
            {
                Log::channel('commands')->info("Lights turned off at {$timeNow}");

                $hue = new Connect;
                $wemo = new Wemo;
                $lights = Light::where('night',1)->get();
                $outlets = Outlet::where('night',1)->get();

                foreach ($lights as $light) {
                    $hue->toggle($light->lightid, False);
                }
                foreach ($outlets as $outlet) {
                    $wemo->toggle($outlet->id, 1);
                }

                $this->info('Turned lights and outlets off at ' . $lightsOffObj->toTimeString());
            }
        } else {

            $this->info('Turning lights and outlets off at ' . $lightsOffObj->toTimeString());

        }
    }
}
