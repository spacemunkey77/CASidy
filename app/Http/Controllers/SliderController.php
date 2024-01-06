<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AutomationSetting;

class SliderController extends Controller
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
        $AutomationSettings = AutomationSetting::find(2);
        $json = json_decode($AutomationSettings->settings);

        $cloudyDay = $json->cloudy;
        $homeAway  = $json->awaylightsoff;
        $homeAutomation = $json->automation;
        $sunriseLights = $json->sunrise;

        $lightOptions = [
            'rainy' => $cloudyDay,
            'homeaway' => $homeAway,
            'automation' => $homeAutomation,
            'sunriselights' => $sunriseLights
        ];

        return response()->json(
            ['options' => $lightOptions]
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);
        $homeAwaysetting = $json->awaylightsoff;
        $cloudySetting   = $json->cloudy;
        $homeAutomation  = $json->automation;
        $sunriseLights  = $json->sunrise;

        switch ($request->get('action')) {
            case 'homeaway';
                if ($homeAwaysetting == 1) {
                    $json->awaylightsoff = 0;
                    $hsettings->settings = json_encode($json);
                    $hsettings->save();
                    return response()->json(
                        ['homeaway' => false]
                    );
                }
                if ($homeAwaysetting == 0) {
                    $json->awaylightsoff = 1;
                    $hsettings->settings = json_encode($json);
                    $hsettings->save();
                    return response()->json(
                        ['homeaway' => true]
                    );
                }
                break;
            case 'rainyday';
                    if ($cloudySetting == 1) {
                        $json->cloudy = 0;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['rainy' => false]
                        );
                    }
                    if ($cloudySetting == 0) {
                        $json->cloudy = 1;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['rainy' => true]
                        );
                    }
                    break;
            case 'automation';
                    if ($homeAutomation == 1) {
                        $json->automation = 0;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['automation' => false]
                        );
                    }
                    if ($homeAutomation == 0) {
                        $json->automation = 1;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['automation' => true]
                        );
                    }
                    break;
            case 'sunriselights';
                    if ($sunriseLights == 1) {
                        $json->sunrise = 0;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['sunriselights' => false]
                        );
                    }
                    if ($sunriseLights == 0) {
                        $json->sunrise = 1;
                        $hsettings->settings = json_encode($json);
                        $hsettings->save();
                        return response()->json(
                            ['sunriselights' => true]
                        );
                    }
                    break;
        }
    }
}
