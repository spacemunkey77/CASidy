<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Outlet;
use App\Models\Light;
use App\Models\AutomationSetting;

class NightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $lights = Light::all();
        $outlets = Outlet::all();
        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);
        $sunsetdelta = $json->sunsetdelta;

        return view('night.index', compact('lights', 'outlets', 'sunsetdelta'));
    }

    public function store(Request $request) {

        $lights = Light::all();
        $outlets = Outlet::all();

        $night_outlets = $request->get('outlets');
        $night_lights = $request->get('lights');

        foreach ($lights as $light) {
            $current_light = Light::where('lightid',$light->lightid)->first();
            if (in_array($light->lightid, $night_lights)) {
                Log::channel('automation')->info("Current Id: {$current_light->lightid}" );
                Log::channel('automation')->info("Night: {$current_light->night}" );
                $current_light->night = 1;
            } else {
                $current_light->night = 0;
            }
            $current_light->save();
        }

        foreach ($outlets as $outlet) {
            $current_outlet = Outlet::find($outlet->id);
            if (in_array($outlet->id, $night_outlets)) {
                $current_outlet->night = 1;
            } else {
                $current_outlet->night = 0;
            }
            $current_outlet->save();
        }

        $hsettings = AutomationSetting::find(2);
        $json = json_decode($hsettings->settings);
        $json->sunsetdelta = (int)$request->get('sunsetdelta');

        $hsettings->settings = json_encode($json);
        $hsettings->save();

        $lights = Light::all();
        $outlets = Outlet::all();

        $sunsetdelta = $json->sunsetdelta;

        return view('night.index', compact('lights', 'outlets', 'sunsetdelta'));

    }

}
