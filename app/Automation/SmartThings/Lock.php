<?php

namespace App\Automation\SmartThings;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;

use \App\Models\AutomationSetting;

class Lock 
{
	private $client;
	private $lockid;

	public function __construct() {

 		$stsettings = AutomationSetting::find(4);
        $json = json_decode($stsettings->settings);

		$oath_token = $json->token;
		$api_url = 'https://api.smartthings.com/v1/';
		$this->lockid = $json->lockid;

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

	public function getStatus() {

		$deviceurl = "devices/{$this->lockid}/status";

		$r = $this->client->request('GET', $deviceurl);

		$json = json_decode($r->getBody());

		$doorStatus    = $json->components->main->contactSensor->contact->value;
		$lockStatus    = $json->components->main->lock->lock->value;
		$batteryStatus = $json->components->main->battery->battery->value;

		$statusArray = [
			"door" => $doorStatus,
			"lock" => $lockStatus,
			"battery" => $batteryStatus
		];

		$dump = json_encode($json);

		return $statusArray;
	}

	public function operate($action) {

		$commandurl = "devices/{$this->lockid}/commands";

		$postJSON = [
			"commands" => [
				[
					"component" => "main",
	      			"capability" => "lock",
	      			"command" => $action
      			]
			]
		];

		try {

			$r = $this->client->request('POST', $commandurl, [
					'json' => $postJSON
				]);

		} catch (ClientException $e) {

            return $e->getMessage();
        }

		$json = json_decode($r->getBody());

		$status = strtolower($json->results[0]->status);

		switch ($status) {
			case "accepted":
				return array(
					"status" => $action . "ed"
				);
				break;
			default:
				return  array(
					"status" => $status
				);
		}	
	}
}	