<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

use App\Automation\Hue\Connect;
use Carbon\Carbon;

use App\Notifier\Notifier;

use App\Models\Powerstatus;
use App\Models\Light;
use App\Moduels\Notify;

class PowerStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casidy:power {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the current Power status for the Condo.';

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
        $ups = new Powerstatus;

        $current = $this->argument('status');

        switch($current) {
            case "on":
                $ups->event = 1;
                break;
            case "off":
                $ups->event = 0;
                if ($this->averageBrightness() > 200) {
                    $this->resetLights();
                    $this->notify();
                }
                break;
            case "main":
                $ups->event = 2;
                if ($this->averageBrightness() > 200) {
                    $this->resetLights();
                    $this->notify();
                }
                break;
            case "check":
                if ($this->averageBrightness() > 200) {
                    $ups->event = 2;
                    $this->resetLights();
                    $this->notify();
                } else {
                    $ups->event = 3;
                }
                break;
        }

        if ($ups->event < 3) {
            $ups->occurred   = Carbon::now();
            $ups->created_at = Carbon::now();
            $ups->updated_at = Carbon::now();
            $ups->save();
            $this->info('Power Status Updated');
        } else {
            $this->info('Power Status Checked');
        }

    }

    private function resetLights() {

        $hue = new Connect;
        $lights = Light::all();

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

            $hue->toggle($light->lightid, False);
        }

        return true;
    }

    private function notify() {

        Notifier::push("mainpoweroff",null,false);

        /* (Currently Disabled)
        // Send text Message through Twilio.

        $notifyList = Notify::where('optin',1)->get();

        $message = "🔌 Mains Power is out. On Battery 🪫.";

        foreach ($notifyList as $notify) 
        {
            Notifier::sms($notify->sms, $message);
        }

        */

        return true;
    }

    private function averageBrightness() {

        $hue = new Connect;
        $lights = Light::all();

        $brightnessAry = array();

        foreach ($lights as $light) {

            $brightnessAry[] = $hue->getbrightness($light->lightid);

        }

        $brightnessAry = array_filter($brightnessAry);

        return array_sum($brightnessAry)/count($brightnessAry);

    }

}
