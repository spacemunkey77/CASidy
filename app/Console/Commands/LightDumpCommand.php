<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Automation\Hue\Connect;
use Illuminate\Support\Facades\Log;

class LightDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:lightdump {light}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log Light Colors Continuously. Specify light id.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $light = $this->argument('light');
        $hue = new Connect;

        for ($i = 0; $i < 10000; $i++) {

            $colorInfo = $hue->getxycolor($light);

            $color = implode(" ", $colorInfo["color"]);
            $bri = $colorInfo["bri"];
            $sat = $colorInfo["sat"];

            Log::channel('lightdump')->info("Color: {$color}  :: Brightness: {$bri}  :: Saturation: {$sat}");
            
            sleep(0.3);
        }

        return true;
    }
}
