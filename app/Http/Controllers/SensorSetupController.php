<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\AutomationSetting;

class SensorSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $AutomationSettings = AutomationSetting::find(1);
        $json = json_decode($AutomationSettings->settings);
        $setup = $json->setup;
        $alarmMode = $json->mode;

        $getTimers = AutomationSetting::find(6);
        $tjson = json_decode($getTimers->settings);
        $hometime_twentyfour = $tjson->nightsafetyevening;

        $carbonTime = Carbon::parse($hometime_twentyfour);

        $hometime = $carbonTime->format('h:i A');

        if ($alarmMode > 0) {
            $brandicon = "alarmarmed.png";
        } else {
            $brandicon = "houseicon.png";
        }

        return view("sensors.index", compact('setup','brandicon','hometime'));
    }

    public function store(Request $request) {

        $action = $request->get('action');

        if ($action == "provision") {
            $apiurl = "http://10.0.77.99:14984/";
            $client = new Client(['base_uri' => $apiurl]);
            $jData["endpoint_type"] = "rest";
            $jData["endpoint"] = "https://casidy.app/api/sensors";
            $jData["token"] = env('KONNECTED_TOKEN');
            $jData['sensors'] = [["pin" => 1],["pin" => 2],["pin" => 5],["pin" => 6],["pin" => 7],["pin" => 9]];

            try {
                $response = $client->put('settings', ['json' => $jData]);

                if ($response->getStatusCode() == 200) {

                    $AutomationSettings = AutomationSetting::find(1);
                    $json = json_decode($AutomationSettings->settings);
                    $json->setup = 1;
                    $AutomationSettings->settings = json_encode($json);
                    $AutomationSettings->save();

                    return response()->json(
                        ['provision' => true]
                    );

                } else {

                    return response()->json(
                        ['provision' => false]
                    );
                }

            } catch (Exception $e) {

                return response()->json(
                    ['provision' => false]
                );
            }
        }
    }
}
