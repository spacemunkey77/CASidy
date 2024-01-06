<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Http\Requests\AlarmRequest;
use App\Automation\Hue\Connect;
use App\Automation\Wemo\Wemo;
use App\Notifier\Notifier;

use App\Mail\AlertEmail;

use App\Models\AlarmEvent;
use App\Models\AutomationSetting;
use App\Models\AlarmZone;
use App\Models\Notify;

use App\Models\Boundary;
use App\Models\Light;
use App\Models\Outlet;

// Log data retreived.
use Illuminate\Support\Facades\Log;

class KonnectedController extends Controller
{
    public function store(Request $request)
    {
        $response_data  = $request->json()->all();

        $asettings = AutomationSetting::find(1);
        $hsettings = AutomationSetting::find(2);
        $json = json_decode($asettings->settings);
        $hjson = json_decode($hsettings->settings);
        $timeNow = Carbon::now()->timezone('America/Chicago');

        // Run if the Sensor Home Automation is turned on


        // Run if the Alarm is Active and Armed.

        if ($this->getAlarmSystemStatus()) {

            switch ($json->mode) {
                case 1:
                    $this->armedAndHome($response_data);
                    break;
                case 2:
                    $this->armedAndAway($response_data);
                    break;
                case 3:
                    break;
            }

        }

        if ($hjson->automation == 1) {
            if (!$this->getAlarmSystemStatus() and 
                $this->getPin($response_data, 9)) {

                if ($json->mode < 1) {
                    Log::channel('automation')->info("The Front Door Sensor detected entry at {$timeNow}");
                    $this->automateTurnOn();
                }
            }
        }

        // Return back to the Konnected.io board that the data was
        // recieved.

        return response()->json(
            ['status' => "success"]
        );
    }

 // Get Alarm System Status 

    private function getAlarmSystemStatus() {
        $asettings = AutomationSetting::find(1);

        $json = json_decode($asettings->settings);

        switch ($json->mode) {
            case 0:
                return false;
                break;
            case 1:
            case 2:
            case 3:
                return true;
        }

    }

    // Get the status of the pins on the Konnected.io board.

    private function getPin($response_data, $searchPin = 0) {

        $pins = array();
        
        if (is_array($response_data)) {
            if (count($response_data) > 2) {
                foreach ($response_data as $data) {
                    if ($data["state"] == 1) {
                        $pins[$data["pin"]] = true;
                    }
                }
            } else {
                if ($response_data["state"] == 1) {
                    $pins[$response_data["pin"]] = true;
                }
            }
            if ($searchPin > 0) {
                if (array_key_exists($searchPin, $pins)) {
                    return true;
                } else { 
                    return false;
                }
            }
            return $pins;
        }
        return false;
    }

    // Check the Pins if in Armed and in Away Mode.

    private function armedAndAway($response_data) {

      if (is_array($response_data)) {

            if (count($response_data) > 2) {

                foreach ($response_data as $data) {

                    if ($data["state"] == 1) {

                        $zoneInfo   = AlarmZone::where('pin', $data["pin"])->first();
                        $zoneDesc   = $zoneInfo->name;

                        $ae = new AlarmEvent;

                        $ae->zone       = $zoneInfo->zone;
                        $ae->event      = $data["state"];
                        $ae->created_at = Carbon::now()->timezone('America/Chicago');

                        $ae->save();

                        $this->sendNotification($zoneInfo->zone);
                    }
                }
            } else {

                if ($response_data["state"] == 1) {

                    $zoneInfo   = AlarmZone::where('pin', $response_data["pin"])->first();
                    $zoneDesc   = $zoneInfo->name;

                    $ae = new AlarmEvent;

                    $ae->zone       = $zoneInfo->zone;
                    $ae->event      = $response_data["state"];
                    $ae->created_at = Carbon::now()->timezone('America/Chicago');

                    $ae->save();

                    $this->sendNotification($zoneInfo->zone);
                }
            }
        }
    }     

    // Check the Pins if in Armed and in Home Mode.

    private function armedAndHome($response_data) {

        // Get Home Pins

        $zones = AlarmZone::where('home', 1)->get();

        $perimeterPins = [];

        foreach($zones as $zone) {
            $perimeterPins[] = (int)$zone["pin"]; 
        }

        if (is_array($response_data)) {
            if (count($response_data) > 2) {
                foreach ($response_data as $data) {

                    if ($data["state"] == 1 && in_array((int)$data["pin"], $perimeterPins)) {

                        $zoneInfo   = AlarmZone::where('pin', $data["pin"])->first();
                        $zoneDesc   = $zoneInfo->name;

                        $ae = new AlarmEvent;

                        $ae->zone       = $zoneInfo->zone;
                        $ae->event      = $data["state"];
                        $ae->created_at = Carbon::now()->timezone('America/Chicago');

                        $ae->save();

                        $this->sendNotification($zoneInfo->zone);
                    }
                }
            } else {

                if ($response_data["state"] == 1 && in_array((int)$response_data["pin"], $perimeterPins)) {

                    $zoneInfo   = AlarmZone::where('pin', $response_data["pin"])->first();
                    $zoneDesc   = $zoneInfo->name;

                    $ae = new AlarmEvent;

                    $ae->zone       = $zoneInfo->zone;
                    $ae->event      = $response_data["state"];
                    $ae->created_at = Carbon::now()->timezone('America/Chicago');

                    $ae->save();

                    $this->sendNotification($zoneInfo->zone);
                }
            }
        }
    }     

    // Change the Lights Rainbow Colored.

    private function candyflossLights() {
        /* #00CF00      rgb(0, 207, 0)
           #FFFF00      rgb(255, 255, 0)
           #FFAE62      rgb(255, 174, 98)
           #FF3029      rgb(255, 48, 41)
           #FF009A      rgb(255, 0, 154)
           #BB2AF4      rgb(187, 42, 244)
           #2B0BFF      rgb(43, 11, 255)
           #00F9F6      rgb(0, 249, 246)
           #FFFFFF      rgb(255, 255, 255)
        */

        $hue = new Connect;

        $lights = Light::where('candyfloss',1)->get();

        $rainbow = [
            [0, 207, 0],
            [255, 255, 0],
            [255, 174, 98],
            [255, 48, 41],
            [255, 0, 154],
            [187, 42, 244],
            [43, 11, 255],
            [0, 249, 246],
            [255, 255, 255],
        ];

        for ($i = 0; $i < 3; $i++) {
            foreach ($rainbow as $color) {
                $rgbtoxy = $hue->convertRGBToXY($color[0], $color[1], $color[2]);
                $colorxy = [$rgbtoxy['x'], $rgbtoxy['y']];
                foreach($lights as $light) {
                    $hue->set_colorxy($light->lightid, $colorxy, 255);
                }
                sleep(0.5);
            }
        }

        foreach($lights as $light) {
            $c = explode(" ", $light->color);
            $color[] = (float)$c[0];
            $color[] = (float)$c[1];
            $hue->set_colorxy($light->lightid, $color, (int)$light->brightness);
            $hue->toggle($light->lightid, False);
        }
    }

    // Blink the lights red for 10 minutes.

    private function blinkinLights() {
        $hue = new Connect;
        $lights = [
            [1,[0.6514, 0.3089],255],
            [2,[0.6750, 0.3220],255],
            [4,[0.6435, 0.3099],255],
            [7,[0.6314, 0.2978],255],
            [9,[0.6358, 0.2891],255],
            [11,[0.6521, 0.3004],255],
            [12,[0.6520, 0.3004],255]
        ];
        for ($i = 0; $i < 120; $i++) {
            if ($this->getAlarmSystemStatus()) {
                foreach ($lights as $light) {
                    $hue->set_colorxy($light[0], $light[1], $light[2]);            
                }
                sleep(0.5);
                foreach ($lights as $light) {
                    $hue->toggle($light[0], False);
                }
            }
        }
    }

    // Get Boundry Light States

    private function getLightStatus() {

        $hue = new Connect;
        $lights = Light::where('boundary',1)->orderBy('lightid')->get();

        foreach($lights as $light) {

            if ($hue->get_light($light->lightid)) {;
                Log::channel('automation')->info("Light {$light->name} on.");
                return true;
            } else {
                Log::channel('automation')->info("Light {$light->name} off.");
            }

        }

        return false;
    }

    // Turn on Outlets and Lights
    private function automateTurnOn() {

        $hue = new Connect;
        $wemo = new Wemo;
        if ($this->isAfterSunset()) {
            $lights = Light::where('night',1)->orderBy('lightid')->get();
        } else {
            $lights = Light::where('boundary',1)->orderBy('lightid')->get();
        }
       
        $outlets = Outlet::where('boundary',1)->get();

        // Time Functions
        $t = new Carbon("22:00:00"); // Don't turn lights on after 10pm
        $sleepytime = $t->timestamp;
        $now = now()->timestamp;
        $timeNow = Carbon::now()->timezone('America/Chicago');

        if (!$this->getLightStatus()) {
            if($now <= $sleepytime) {

                $this->candyflossLights();

                foreach ($lights as $light) {
                    Log::channel('automation')->info("Sensor activated Light {$light->name} turned on at {$timeNow}");
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
        }
    }

    // Send out a notification via Vonage.

    private function sendNotification($zone) {

        $zoneInfo   = AlarmZone::where('zone', $zone)->first();
        $zoneDesc   = $zoneInfo->name;

        $asettings  = AutomationSetting::find(1);
        $json       = json_decode($asettings->settings);

        if ($json->testing == 0) {

            $notifyList = Notify::where('optin',1)->get();

        } else {

            $notifyList = Notify::where('testing',1)->get();

        }

        foreach ($notifyList as $notify)
        {
            if ($json->testing == 0) {

                switch($json->mode) {
                    case 2:
                        $message = "🚨🔔 POSSIBLE BREAK IN! \n {$zoneDesc} Sensor tripped.\nTime: " . now() ."\n Call ☎️ {$notify->alarm_notify_number}";
                        break;
                    case 1:
                        $message = "🚨🔔 INTRUDER ALERT!\n {$zoneDesc} Sensor tripped.\n Time: " . now();
                        break;
                }

            } else {

                $message = "TESTING! TESTING! TESTING!.\n {$zoneDesc} Sensor tripped.\nTime: " . now();

            }

            $this->sendAlert($notify->sms, $message);

            if ($json->testing == 0) {
                $this->blinkinLights();
            }
        }
    }

    private function isAfterSunset() {
        $now = Carbon::now();

        // Get sunset time for today
        $sunsetTime = date_sunset(time(), SUNFUNCS_RET_STRING, 36.025993391064446, -86.71146171961897);

        // Convert sunset time to a Carbon instance
        $sunset = Carbon::createFromFormat('H:i', $sunsetTime);

        // Compare times
        return $now->greaterThan($sunset);
    }

    private function sendAlert($smsphone, $message) {

        #Notifier::sms($smsphone, $message);
        Notifier::push("alarm_event", $message);
        
        return true;

    }
}
