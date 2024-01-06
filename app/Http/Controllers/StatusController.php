<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Automation\SmartThings\Lock;
use App\Automation\Nest\Nest;

use App\Models\Boundary;
use App\Models\Door;
use App\Models\AlarmEvent;
use App\Models\EventReport;
use App\Models\Doorbell;
use App\Models\Powerstatus;
use App\Models\AutomationSetting;
use App\Models\WeatherData;

use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('status.status');
    }

    public function ajax() {
        $asettings = AutomationSetting::find(1);
        $ajson = json_decode($asettings->settings);
        $alarmMode = $ajson->mode;

        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);
        $sunsetdelta = $json->sunsetdelta;

        // Get Times 

        $lightsOn     = Carbon::createFromTimestamp($json->lotime, 'America/Chicago');
        $sunset       = Carbon::createFromTimestamp($json->sutime, 'America/Chicago');
        $homeAwayTime = Carbon::createFromTimestamp($json->hatime, 'America/Chicago');
        $armedTime    = Carbon::createFromTimestamp($ajson->armed, 'America/Chicago');
        $disarmedTime = Carbon::createFromTimestamp($ajson->disarmed, 'America/Chicago');

        // Get System Status 

        $status['augustlock']  = $this->getAugustStatus();
        $status['door']        = $this->getDoorStatus();
        $status["thermostat"]  = $this->getThermostatTemps();
        $status["outdoorfahr"] = $this->getWeatherTemp();
        $status['homeaway']    = $homeAwayTime->format('h:i:s A');
        $status['lightson']    = $lightsOn->format('h:i:s A');
        $status['sunset']      = $sunset->format('h:i:s A');
        $status['armed']       = $armedTime->toDateTimeString();
        $status['disarmed']    = $disarmedTime->toDateTimeString();

        if (isset($status['thermostat']['redir'])) {
            return array("redir" => $status['thermostat']['redir']);
        }

        // Get Counts for showing/hiding status page links 

        $door = Door::all();
        $db = Doorbell::all();
        $ae = AlarmEvent::all();
        $ne = Boundary::all();

        $status['count']['entries'] = $door->count();
        $status['count']['doorbell'] = $db->count();
        $status['count']['alarms'] = $ae->count();
        $status['count']['boundary'] = $ne->count();

        if ($ae->count() > 0) {
            $ale = AlarmEvent::latest()->first();
            $status['lastalarmevent'] = $ale->created_at->toDateTimeString();
        }

        // Changing Icon and Background Colors

        // Set background for august battery percentage.

        if ($status['augustlock']['battery'] > 50) {
            $status['augustlock']['color'] = "#00DA00";
        } elseif ($status['augustlock']['battery'] > 30 &&
                  $status['augustlock']['battery'] < 50) {
            $status['augustlock']['color'] = "#EDDF00";
        } else {
            $status['augustlock']['color'] = "#FF0032";
        }

        // Set the NavBar Brand to either Armed or Disarmed Logo 

        if ($alarmMode > 0) {
            $status['brandicon'] = "alarmarmed.png";
        } else {
            $status['brandicon'] = "houseicon.png";
        }

        // Set the Logo color for the Alarm Panel

        switch ($alarmMode) {
            case 1:
                $status['alarmarmed'] = 'alarmhome.svg';
                break;
            case 2:
                $status['alarmarmed'] = 'alarmarmed.svg';
                break;
            default:
                $status['alarmarmed'] = 'houseshield.svg';     
        }

        // Set the logo color for the August panel

        switch($status['augustlock']['doorBolt']) {
            case "Locked":
                $status['dooricon'] = 'doorlock.svg';
                break;
            case "Unlocked":
                $status['dooricon'] = 'doorunlock.svg';
        }

        // Set the logo color for the Thermostat panel

        switch($status['thermostat']['mode']) {
            case "heat":
                if ($status["thermostat"]['status'] == "heating") {
                    $status['thermostatico'] = 'heating.svg';
                } else {
                    $status['thermostatico'] = 'thermostat.svg';
                }
                break;
            case "cool":
                if ($status["thermostat"]['status'] == "cooling") {
                    $status['thermostatico'] = 'cooling.svg';
                } else {
                    $status['thermostatico'] = 'thermostat.svg';
                }
                break;
            case "off":
                $status['thermostatico'] = 'thermostat.svg';
        }     

        return response()->json($status);
    }

    private function getAugustStatus() 
    {
        $lock = new Lock();
        $auguststatus = $lock->getStatus();

        return array(
            "doorBolt" => ucfirst($auguststatus["lock"]),
            "battery"  => $auguststatus["battery"]
        );
    }

    private function getDoorStatus() 
    {
        $lastentry = Door::orderBy('doorstamp','desc')->first();

        if (isset($lastentry->dooraction)) {
            if ($lastentry->dooraction == 0) {
                $status = "{$lastentry->door} last opened at {$lastentry->doorstamp} by {$lastentry->who}";
            } else {
                $status = "{$lastentry->door} last closed at {$lastentry->doorstamp} by {$lastentry->who}";
            }
        } else {
            $status = "";
        }

        return $status;
    }

    private function getThermostatTemps() 
    {
        $nest = new Nest();
        
        $thermostat = $nest->thermostat()[0];

        return $thermostat;

    }

    private function getWeatherTemp()
    {
        $weather = WeatherData::latest()->first();

        return round($weather->TempFahr);
    }

    public function doorbell()
    {
        $entries = Doorbell::orderBy('dooraction', 'desc')->orderBy('created_at','desc')->take(7)->get();
        return view('doorbell.index', ['entries' => $entries]);
    }

    public function activity()
    {
        $entries = Boundary::orderBy('created_at','desc')->get();
        return view('boundary.index', ['entries' => $entries]);
    }

    public function power() 
    {
        $bsettings = AutomationSetting::find(3);
        $batteryinfo = json_decode($bsettings->settings);

        $powerstatus = Powerstatus::orderBy('occurred','desc')->get();

        return view('status.power', compact('powerstatus','batteryinfo'));
    }

    public function sensors() 
    {
        $AutomationSettings = AutomationSetting::find(1);
        $json = json_decode($AutomationSettings->settings);
        $alarmMode = $json->mode;

        if ($alarmMode > 0) {
            $brandicon = "alarmarmed.png";
        } else {
            $brandicon = "houseicon.png";
        }

        $events = EventReport::orderBy('event','desc')->get();
        return view('sensors.report', compact('events','brandicon'));
    }

    public function door()
    {
        $entries = Door::orderBy('updated_at','desc')->
                         orderBy('dooraction','desc')->
                         take(6)->
                         get();
        return view('door.index', ['entries' => $entries]);
    }

}