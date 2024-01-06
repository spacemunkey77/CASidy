<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\AutomationSetting;

class HomeAwaySetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:lightsofftime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set time for lights to turn off at night when away.';

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

        $offHr = 21;
        $offMn = rand(0, 59);

        $dt = Carbon::now()->timezone('America/Chicago');

        $datePart = $dt->toDateString();

        $lightsOffStr = "{$datePart} {$offHr}:{$offMn}";

        $lightsOffObj = new Carbon($lightsOffStr);

        $lightsOffTs = $lightsOffObj->timestamp;

        $json->hatime = $lightsOffTs;

        $hsettings->settings = json_encode($json);

        $hsettings->save();

        $this->info('Turning lights off at ' . $lightsOffObj->toTimeString());
    }
}
