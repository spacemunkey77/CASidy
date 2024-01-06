<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\AutomationSetting;

class SensorOptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $AutomationSettings = AutomationSetting::find(1);
        $json = json_decode($AutomationSettings->settings);

        switch ($json->mode) {
            case 0:
                $active = false;
                $away = false;
                break;
            case 1:
                $active = true;
                $away = false;
                break;
            case 2:
                $active = false;
                $away = true;
                break;
            case 3:
                $active = false;
                $away = false;
        }

        $testing     = $json->testing;
        $testing     = $json->testing;
        $nightsafety = $json->night;

        $sensorOptions = [
            'active' => $active,
            'away' => $away,
            'testing' => $testing,
            'nightsafety' => $nightsafety
        ];

        return response()->json(
            ['options' => $sensorOptions]
        );
    }

    public function store(Request $request)
    {
        $asettings = AutomationSetting::find(1);
        $json = json_decode($asettings->settings);
        $alarmMode = $json->mode;
        $testingSetting = $json->testing;
        $nightSafety = $json->night;
        $timeNow = Carbon::now()->timezone('America/Chicago');

        switch ($request->get('action')) {
            case 'activatesensor';
                if ($alarmMode == 1) {
                    $json->mode = 0;
                    $json->disarmed = $timeNow->timestamp;
                    $asettings->settings = json_encode($json);
                    $asettings->save();
                    return response()->json(
                        ['active' => false]
                    );
                }
                if ($alarmMode == 0) {
                    $json->mode = 1;
                    $json->armed = $timeNow->timestamp;
                    $asettings->settings = json_encode($json);
                    $asettings->save();
                    return response()->json(
                        ['active' => true]
                    );
                }
                break;
            case 'away';
                    if ($alarmMode == 2) {
                        $json->mode = 0;
                        $json->disarmed = $timeNow->timestamp;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['away' => false]
                        );
                    }
                    if ($alarmMode < 2) {
                        $json->mode = 2;
                        $json->armed = $timeNow->timestamp;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['away' => true]
                        );
                    }
                    break;
            case 'testing';
                    if ($testingSetting == 1) {
                        $json->testing = 0;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['testing' => false]
                        );
                    }
                    if ($testingSetting == 0) {
                        $json->testing = 1;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['testing' => true]
                        );
                    }
                    break;
            case 'nightsafety';
                    if ($nightSafety == 1) {
                        $json->night = 0;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['nightsafety' => false]
                        );
                    }
                    if ($nightSafety == 0) {
                        $json->night = 1;
                        $asettings->settings = json_encode($json);
                        $asettings->save();
                        return response()->json(
                            ['nightsafety' => true]
                        );
                    }
                    break;
        }
    }
}
