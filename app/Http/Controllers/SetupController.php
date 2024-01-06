<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Automation\Hue\Setup;

use App\Models\Light;
use App\Models\AutomationSetting;

class SetupController extends Controller
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

        if ($alarmMode > 0) {
            $brandicon = "alarmarmed.png";
        } else {
            $brandicon = "houseicon.png";
        }

        return view('setup.index', compact('setup','brandicon'));
    }

    public function store(Request $request) {
        return view('setup.index');
    }

    public function reset() {
        $lights = Light::all();
        $light_setup = new Setup();
        foreach ($lights as $light) {
            $light_setup->remove($light->lightid);
        }
        $light_setup->populate();
        return view('setup.index');
    }

}
