<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Light;

class CloudyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $lights = Light::all();

        return view('cloudy.index', compact('lights'));
    }

    public function store(Request $request) {

        $lights = Light::all();

        $cloudy_lights = $request->get('lights');

        foreach ($lights as $light) {
            $current_light = Light::where('lightid', $light->lightid)->first();
            if (in_array($light->lightid, $cloudy_lights)) {
                Log::channel('automation')->info("Current Id: {$current_light->lightid}" );
                Log::channel('automation')->info("Cloudy: {$current_light->night}" );
                $current_light->cloudy = 1;
            } else {
                $current_light->cloudy = 0;
            }
            $current_light->save();
        }

        return view('cloudy.index', compact('lights'));
    }
}
