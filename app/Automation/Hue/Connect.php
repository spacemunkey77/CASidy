<?php

namespace App\Automation\Hue;

use GuzzleHttp\Client;

use \App\Models\Light;

class Connect
{

    private $apiurl;

    /* $light refers to lightid of light */

    public function __construct() {
        $huekey = env('HUE_API_KEY');
        $huebrigeip = env("HUE_BRIDGE_IP");
        $this->apiurl = "https://{$huebrigeip}/api/{$huekey}/";
    }

    /* HTTP Get Functions */

    public function get_light($light) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $getLight = "lights/{$light}";
        $response = $client->request('GET', $getLight);
        $light = json_decode($response->getBody());
        if ( $light->state->on ) {
            return True;
        } else {
            return False;
        }
    }

    public function lightname($light) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $getLight = "lights/{$light}";
        $response = $client->request('GET', $getLight);
        $light = json_decode($response->getBody());
        return $light->name;
    }

    public function getbrightness($light) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $getLight = "lights/{$light}";
        $response = $client->request('GET', $getLight);
        $light = json_decode($response->getBody());
        return $light->state->bri;
    }

    public function getxycolor($light) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $getLight = "lights/{$light}";
        $response = $client->request('GET', $getLight);
        $light = json_decode($response->getBody());
        return array(
            "color" => $light->state->xy,
            "bri"   => $light->state->bri,
            "sat"   => $light->state->sat
        );
    }

    /* HTTP Post Functions */

    public function setbrightness($light, $brightness) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $setState = "lights/{$light}/state";
        $json["on"] = True;
        $json["bri"] = $brightness;
        $jData = json_encode($json);
        $response = $client->request('PUT', $setState, ['body' => $jData]);
        return true;
    }

    public function toggle($light, $toggle_switch = False) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $setState = "lights/{$light}/state";
        if ($toggle_switch) {
            $json["on"] = True;
        } else {
            $json["on"] = False;
        }
        $jData = json_encode($json);
        $response = $client->request('PUT', $setState, ['body' => $jData]);
        return true;
    }

    public function set_colorxy($light, $color, $brightness, $sat = 0) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $setState = "lights/{$light}/state";
        if ($sat > 0) {
            $json["sat"] = $sat;
        }
        $json["on" ] = True;
        $json["xy"]  = $color;
        $json["bri"] = $brightness;
        $jData = json_encode($json);
        $response = $client->request('PUT', $setState, ['body' => $jData]);
        return true;
    }

    public function set_colorct($light, $colortemp, $brightness) {
        $client = new Client(['base_uri' => $this->apiurl,
                              'verify' => false]);
        $setState = "lights/{$light}/state";
        $json["on" ] = True;
        $json["ct"]  = $colortemp;
        $json["bri"] = $brightness;
        $jData = json_encode($json);
        $response = $client->request('PUT', $setState, ['body' => $jData]);
        return true;
    }

    public static function convertRGBToXY($red, $green, $blue)
    {
        // Normalize the values to 1
        $normalizedToOne['red'] = $red / 255;
        $normalizedToOne['green'] = $green / 255;
        $normalizedToOne['blue'] = $blue / 255;

        // Make colors more vivid
        foreach ($normalizedToOne as $key => $normalized) {
            if ($normalized > 0.04045) {
                $color[$key] = pow(($normalized + 0.055) / (1.0 + 0.055), 2.4);
            } else {
                $color[$key] = $normalized / 12.92;
            }
        }

        // Convert to XYZ using the Wide RGB D65 formula
        $xyz['x'] = $color['red'] * 0.664511 + $color['green'] * 0.154324 + $color['blue'] * 0.162028;
        $xyz['y'] = $color['red'] * 0.283881 + $color['green'] * 0.668433 + $color['blue'] * 0.047685;
        $xyz['z'] = $color['red'] * 0.000000 + $color['green'] * 0.072310 + $color['blue'] * 0.986039;

        // Calculate the x/y values
        if (array_sum($xyz) == 0) {
            $x = 0;
            $y = 0;
        } else {
            $x = $xyz['x'] / array_sum($xyz);
            $y = $xyz['y'] / array_sum($xyz);
        }

        return array(
            'x'   => $x,
            'y'   => $y,
            'bri' => round($xyz['y'] * 255)
        );
    }

}
