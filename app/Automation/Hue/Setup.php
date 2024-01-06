<?php

namespace App\Automation\Hue;

use GuzzleHttp\Client;

use \App\Models\Light;

class Setup
{
    public function __construct() {
        $huekey = env('HUE_API_KEY');
        $huebrigeip = env("HUE_BRIDGE_IP");
        $this->apiurl = "http://{$huebrigeip}/api/{$huekey}/";
    }

    public function populate() {
        $client = new Client(['base_uri' => $this->apiurl]);
        $getLight = "lights";
        $response = $client->request('GET', $getLight);
        $hueLights = json_decode($response->getBody());
        foreach ($hueLights as $key => $hueLight) {
            if (!Light::where('lightid',$key)->first()) {
                if (isset($hueLight->state->colormode)) {
                    switch ($hueLight->state->colormode) {
                        case "xy":
                            $light = new Light;
                            $light->lightid = $key;
                            $light->name = $hueLight->name;
                            $light->kind = $hueLight->state->colormode;
                            $light->brightness = $hueLight->state->bri;
                            $light->color = $hueLight->state->xy[0] . " " . $hueLight->state->xy[1];
                            $light->save();
                            break;
                        case "ct":
                            $light = new Light;
                            $light->lightid = $key;
                            $light->name = $hueLight->name;
                            $light->kind = $hueLight->state->colormode;
                            $light->brightness = $hueLight->state->bri;
                            $light->mired = $hueLight->state->ct;
                            $light->save();
                            break;
                    }
                } else {
                    $light = new Light;
                    $light->lightid = $key;
                    $light->name = $hueLight->name;
                    $light->kind = "dw";
                    $light->brightness = $hueLight->state->bri;
                    $light->save();
                }
            }
        }

        return true;
    }

    public function remove($light) {
        $light = Light::where('lightid',$light)->first();
        $light->delete();
        $light->save();
        return true;
    }
}
