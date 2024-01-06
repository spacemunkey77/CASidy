<?php

namespace App\Automation\SmartThings;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;

use \App\Models\AutomationSetting;

class Devices 
{
	private $client;
	private $lockid;

	public function __construct() {

 		$stsettings = AutomationSetting::find(4);
        $json = json_decode($stsettings->settings);

		$oath_token = $json->token;
		$api_url = 'https://api.smartthings.com/v1/';

		Log::channel('automation')->info("SmartThings: Using Token {$oath_token}");

		$jar = new CookieJar();

        $this->client = new Client([
        	'base_uri' => $api_url,
            'cookies' => $jar,
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $oath_token,
              ]
            ]);
	}

	public function getDevices() {

		$r = $this->client->request('GET', 'devices');

		$json = json_decode($r->getBody());

		return $json;

	}

}


