<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LightSwitch;
use App\Models\Light;

class ButtonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $lswitches = LightSwitch::orderBy('switch')->get();

        $switches = [];

        foreach($lswitches as $lSwitch) {
            $slights = $lSwitch->lights;
            foreach ($slights as $light) {
                $switches[$lSwitch->switchdesc][] = $light->id;
            }
        }

        $lights = Light::orderBy('name')->get();

        return view('setup.button', compact('switches','lights'));
    }

    public function store(Request $request) {

        $buttons = $request->get('switches');

        foreach ($buttons as $key => $button) {

            $fButton = LightSwitch::where('switchdesc',$key)->first();
            $lights  = $fButton->lights;
            $fButton->lights()->detach($lights);
            $fButton->save();

            $aLight = Light::find($button);
            $fButton->lights()->attach($aLight);
            $fButton->save();

        }

        $lswitches = LightSwitch::all();

        $switches = [];

        foreach($lswitches as $lSwitch) {
            $slights = $lSwitch->lights;
            foreach ($slights as $light) {
                $switches[$lSwitch->switch][] = $light->id;
            }
        }

        $lights = Light::all();

        return view('setup.button', compact('switches','lights'));
    }
}
