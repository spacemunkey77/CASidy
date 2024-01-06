<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Automation\Hue\Connect;
use App\Automation\Wemo\Wemo;
use Carbon\Carbon;

use App\Models\Outlet;
use App\Models\LightSwitch;
use App\Models\AutomationSetting;

class CondoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if ($this->authorize('control_lights')) {

            $AutomationSettings = AutomationSetting::find(1);
            $json = json_decode($AutomationSettings->settings);
            $alarmMode = $json->mode;

            if ($alarmMode > 0) {
                $brandicon = "alarmarmed.png";
            } else {
                $brandicon = "houseicon.png";
            }

            $switches = LightSwitch::all();
            $outlets = Outlet::all();
            return view('condo.index', compact('switches', 'outlets', 'brandicon'));

        }
    }

    public function lightswitch(Request $request)
    {
        $hue = new Connect;
        $hueScene = $request->get('room');
        $lights = LightSwitch::where('switch', $hueScene)->first()->lights;
        foreach ($lights as $light) {
            if ($hue->get_light($light->lightid)) {
                $hue->toggle($light->lightid);
            } else {
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

        }
        return response()->json(
            ['status' => "success"]
        );
    }

    public function outletswitch(Request $request)
    {
        $wemoOutlet = $request->get('outlet');

        $wemoInfo = Outlet::whereName($wemoOutlet)->first();

        $wemoControl = new Wemo;

        $wemoControl->toggle($wemoInfo->id);

        return response()->json(
            ['status' => "success"]
        );
    }
}
