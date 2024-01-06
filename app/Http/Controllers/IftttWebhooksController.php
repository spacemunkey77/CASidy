<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AugustRequest;
use App\Http\Requests\DoorbellRequest;
use App\Http\Requests\GPSRequest;
use App\Http\Requests\ButtonRequest;

use App\Automation\Hue\Connect;
use App\Automation\Wemo\Wemo;
use App\Automation\SmartThings\Lock;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Door;
use App\Models\AutomationSetting;
use App\Models\Doorbell;
use App\Models\Outlet;
use App\Models\Light;
use App\Models\Boundary;

class IftttWebhooksController extends Controller
{
    public function august(AugustRequest $request)
    {
        $doorAction = $request->status;
        $whichDoor  = $request->door;
        $lockPerson  = $request->who;
        $timestamp  = str_replace(" at "," ",$request->timestamp);
        $timeNow = Carbon::now()->timezone('America/Chicago');


        if ($doorAction == "unlocked") {
            $door = new Door;
            $door->door = $whichDoor;
            $door->who = $lockPerson;
            $door->doorstamp = Carbon::parse($timestamp, "America/Chicago");
            $door->dooraction = 0;
            $door->save();

            if (User::where('name', $lockPerson)->exists()) {

                $asettings = AutomationSetting::find(1);
                $ajson = json_decode($asettings->settings);

                if ($ajson->mode == 1 or $ajson->mode == 2) {
                    $ajson->mode = 0;
                    $ajson->disarmed = $timeNow->timestamp;

                    Log::channel('automation')->info("Disarmed Alarm System at {$timeNow}");

                    $asettings->settings = json_encode($ajson);
                    $asettings->save();
                }

            }

            return response()->json(
                ['status' => "success"]
            );
        }
        if ($doorAction == "locked") {
            $door = new Door;
            $door->door = $whichDoor;
            $door->who = $lockPerson;
            $door->doorstamp = Carbon::parse($timestamp, "America/Chicago");
            $door->dooraction = 1;
            $door->save();

            if (User::where('name', $lockPerson)->exists()) {

                $asettings = AutomationSetting::find(1);
                $ajson = json_decode($asettings->settings);

                if ($ajson->mode == 3) {
                    $ajson->mode = 2;
                    $ajson->armed = $timeNow->timestamp;
                    
                    Log::channel('automation')->info("Armed Alarm System at {$timeNow}");

                    $asettings->settings = json_encode($ajson);
                    $asettings->save();
                }

            }           

            return response()->json(
                ['status' => "success"]
            );
        }
    }

    public function arlo(DoorbellRequest $request)
    {
        $doorAction = $request->status;
        $whichDoorbell  = $request->doorbell;
        $object = $request->object;
        $timestamp  = str_replace(" at "," ",$request->timestamp);

        if ($doorAction == "motion") {
            $doorbell = new Doorbell;
            $doorbell->doorbell = $whichDoorbell;
            $doorbell->created_at = Carbon::parse($timestamp, "America/Chicago");
            $doorbell->dooraction = 1;
            $doorbell->object = $object;
            $doorbell->save();
        }
        if ($doorAction == "rung") {
            $doorbell = new Doorbell;
            $doorbell->doorbell = $whichDoorbell;
            $doorbell->created_at = Carbon::parse($timestamp, "America/Chicago");
            $doorbell->dooraction = 2;
            $doorbell->object = "Person";
            $doorbell->save();
        }

        return response()->json(
            ['status' => "success"]
        );
    }

    public function gpstrigger(GPSRequest $request)
    {
        $status = $request->status;
        $timestamp = $request->timestamp;
        $timeNow = Carbon::now()->timezone('America/Chicago');

        $hue = new Connect;
        $wemo = new Wemo;

        if ($status == "exited") {

            Log::channel('automation')->info("GPS detects you left the area at {$timeNow}");

            $lights = Light::all();
            $outlets = Outlet::where('boundary','1')->get();

            foreach ($lights as $light) {
                $hue->toggle($light->lightid, False);
            }
            foreach ($outlets as $outlet) {
                $wemo->toggle($outlet->id, 1);
            }

            $lock = new Lock();
            $r = $lock->operate("lock");

            $asettings = AutomationSetting::find(1);
            $ajson = json_decode($asettings->settings);

            $ajson->mode = 2;
            $ajson->armed = $timeNow->timestamp;

            $asettings->settings = json_encode($ajson);
            $asettings->save();

            $hsettings = AutomationSetting::find(2);
            $hjson = json_decode($hsettings->settings);

            $hjson->cloudy = 0;

            $hsettings->settings = json_encode($hjson);
            $hsettings->save();

        }
        elseif ($status == "entered") {

            Log::channel('automation')->info("GPS detects you entered the area at {$timeNow}");

            $asettings = AutomationSetting::find(1);
            $ajson = json_decode($asettings->settings);

            $ajson->mode = 0;
            $ajson->disarmed = $timeNow->timestamp;

            $asettings->settings = json_encode($ajson);
            $asettings->save();

        }

        $bounds = new Boundary;
        $bounds->status = $status;
        $bounds->created_at = Carbon::parse($timestamp, "America/Chicago");
        $bounds->save();

        return response()->json(
            ['status' => "success"]
        );
    }

    public function button(ButtonRequest $request)
    {
        $action = $request->action;
        $timestamp = $request->timestamp;

        Log::channel('automation')->info("Leaving Home Button pressed at {$timestamp}");

        if ($action == "leaving") {
            $this->turnLightsOff();
            $this->turnOutletsOff();
            $this->primeAlarm();
            $lock = new Lock();
            $r = $lock->operate("unlock");
        }

        return response()->json(
            ['status' => "success"]
        );

    }

    private function turnLightsOff() {
        $hue = new Connect;
        $lights = Light::all();

        foreach ($lights as $light) {
            $hue->toggle($light->lightid, False);
        }
    }

    private function turnOutletsOff() {
        $wemo = new Wemo;
        $outlets = Outlet::where('boundary','1')->get();

        foreach ($outlets as $outlet) {
            $wemo->toggle($outlet->id, 1);
        }
    }

    private function primeAlarm() {
        $asettings = AutomationSetting::find(1);
        $json = json_decode($asettings->settings);

        $json->mode = 3;

        $asettings->settings = json_encode($json);
        $asettings->save();
    }    
}
