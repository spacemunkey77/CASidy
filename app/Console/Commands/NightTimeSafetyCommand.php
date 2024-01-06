<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\AutomationSetting;
use App\Automation\SmartThings\Lock;
use Carbon\Carbon;

// Log data retreived.
use Illuminate\Support\Facades\Log;


class NightTimeSafetyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:nightsafety';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle the Armed status of the Alarm at night and sunrise.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lock = new Lock;
        $alarm = AutomationSetting::find(1);
        $json = json_decode($alarm->settings);
        $timeNow = Carbon::now()->timezone('America/Chicago');
  
        if ($json->night == 1) {
            if ($json->mode == 1) {
                $json->mode = 0;
                $json->disarmed = $timeNow->timestamp;
            } else {
                if ($timeNow->hour > 6) {
                    $lock->operate("lock");
                    $json->mode = 1;
                    $json->armed = $timeNow->timestamp;
                }
            }
        }

        $alarm->settings = json_encode($json);
        $alarm->save();

        return true;       
    }
}
