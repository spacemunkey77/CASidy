<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutomationSetting;

class TimerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $asettings = AutomationSetting::find(6);
        $json = json_decode($asettings->settings);

        $timer['sunsetfrom']         = $json->sunsetfrom;
        $timer['sunsetto']           = $json->sunsetto;
        $timer['lightsofffrom']      = $json->lightsofffrom;
        $timer['lightsoffto']        = $json->lightsoffto;
        $timer['cloudylightsfrom']   = $json->cloudylightsfrom;
        $timer['cloudylightsto']     = $json->cloudylightsto;
        $timer['lightsofftime']      = $json->lightsofftime;
        $timer['nightsafetyevening'] = $json->nightsafetyevening;
        $timer['nightsafetymorning'] = $json->nightsafetymorning;
        $timer['sunrisetime']        = $json->sunrisetime;    

        return view("timers.index", compact('timer'));
    }

    public function store(Request $request) {

        $asettings = AutomationSetting::find(6);
        $json = json_decode($asettings->settings);

        $json->sunsetfrom = $request->sunsetfrom;
        $json->sunsetto = $request->sunsetto;
        $json->lightsofffrom = $request->lightsofffrom;
        $json->lightsoffto = $request->lightsoffto;
        $json->cloudylightsfrom = $request->cloudylightsfrom;
        $json->cloudylightsto = $request->cloudylightsto;
        $json->lightsofftime = $request->lightsofftime;
        $json->nightsafetyevening = $request->nightsafetyevening;
        $json->nightsafetymorning = $request->nightsafetymorning;
        $json->sunrisetime = $request->sunrisetime;    

        $asettings->settings = json_encode($json);

        $asettings->save();

        $timer['sunsetfrom']         = $json->sunsetfrom;
        $timer['sunsetto']           = $json->sunsetto;
        $timer['lightsofffrom']      = $json->lightsofffrom;
        $timer['lightsoffto']        = $json->lightsoffto;
        $timer['cloudylightsfrom']   = $json->cloudylightsfrom;
        $timer['cloudylightsto']     = $json->cloudylightsto;
        $timer['lightsofftime']      = $json->lightsofftime;
        $timer['nightsafetyevening'] = $json->nightsafetyevening;
        $timer['nightsafetymorning'] = $json->nightsafetymorning;
        $timer['sunrisetime']        = $json->sunrisetime;    

        return view("timers.index", compact('timer'));
        
    }
}
