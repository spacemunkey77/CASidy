<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AutomationSetting;
use Carbon\Carbon;


class BatteryStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:apc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the status of the APC Back-UPS ES 450';

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
     * @return int
     */
    public function handle()
    {
        $bsettings = AutomationSetting::find(3);
        $json = json_decode($bsettings->settings);

        $apcinfo = shell_exec("/usr/sbin/apcaccess");

        preg_match("/STATUS\s+:\s([\w\s]+)\s?\n/i", $apcinfo, $matches0);
        preg_match("/LINEV\s+:\s([\d\.]+)\sVolts/i", $apcinfo, $matches1);
        preg_match("/LOADPCT\s+:\s([\d\.]+)\sPercent/i", $apcinfo, $matches2);
        preg_match("/BCHARGE\s+:\s([\d\.]+)\sPercent/i", $apcinfo, $matches3);
        preg_match("/TIMELEFT\s+:\s([\d\.]+)\sMinutes/i", $apcinfo, $matches4);

        /* 

        {"batterystatus": "", "linevoltage": "", "loadpercent": "", "batterycharge": "", "timeleft":""}

        */

        $json->batterystatus = ucwords(strtolower($matches0[1]));
        $json->linevoltage   = $matches1[1];
        $json->loadpercent   = $matches2[1];
        $json->batterycharge = $matches3[1];
        $json->timeleft      = $matches4[1];

        $bsettings->settings = json_encode($json);

        $bsettings->save();

        $this->info('Checking APC Back-UPS ES 450 Status ' . Carbon::now());
    }
}
