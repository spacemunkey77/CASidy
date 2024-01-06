<?php

namespace App\Automation\Nest;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

use \App\Models\AutomationSetting;

class Nest 
{
	private $client;
	private $get_perm_url;

	public function __construct() {

 		$stsettings = AutomationSetting::find(5);
        $json = json_decode($stsettings->settings);

 		$project_id      = $json->{"project-id"};
 		$refresh_token   = $json->{"refresh-token"};
 		$oauth_client_id = $json->{"oauth-client-id"};
 		$oauth_secret    = $json->{"oauth-secret"};
 		$gauth_redir	 = "https://casidy.app/gauth";

		$jar = new CookieJar();

		$api_url = "https://smartdevicemanagement.googleapis.com/v1/enterprises/{$project_id}/";

		$oauth_refresh_url = "https://www.googleapis.com/oauth2/v4/token?client_id={$oauth_client_id}.apps.googleusercontent.com&client_secret={$oauth_secret}&refresh_token={$refresh_token}&grant_type=refresh_token";

		$this->get_perm_url = "https://nestservices.google.com/partnerconnections/{$project_id}/auth?redirect_uri={$gauth_redir}&access_type=offline&prompt=consent&client_id={$oauth_client_id}.apps.googleusercontent.com&response_type=code&scope=https://www.googleapis.com/auth/sdm.service";

        $oauth_client = new Client([
            'cookies' => $jar,
            'headers' => [
                'content-type' => 'application/json',
            ]
        ]);

        try {
        	$r = $oauth_client->request('POST', $oauth_refresh_url);

        	$json = json_decode($r->getBody());

			$oauth_token = $json->access_token;

			Log::channel('automation')->info("Google Token: {$oauth_token}");

        	$this->client = new Client([
        		'base_uri' => $api_url,
            	'cookies' => $jar,
            	'headers' => [
                	'content-type' => 'application/json',
                	'Authorization' => 'Bearer ' . $oauth_token,
              	]
        	]);

        } catch (ClientException $e) {
        	Log::channel('automation')->info("OAuth Error: " . Psr7\Message::toString($e->getResponse()));   	
        }

	}

	public function thermostat() {

		$deviceurl = "devices";

		if (isset($this->client)) {

			$r = $this->client->request('GET', $deviceurl);

			$json = json_decode($r->getBody());

			$tHumidity = $json->devices[0]->traits->{"sdm.devices.traits.Humidity"}->ambientHumidityPercent;

			$thermostatMode = strtolower($json->devices[0]->traits->{"sdm.devices.traits.ThermostatMode"}->mode);

			if ($thermostatMode != "off") {

				switch(strtolower($thermostatMode)) {

					case "heat":

						$tSetTempC = $json->devices[0]->traits->{"sdm.devices.traits.ThermostatTemperatureSetpoint"}->heatCelsius;
						break;

					case "cool":

						$tSetTempC = $json->devices[0]->traits->{"sdm.devices.traits.ThermostatTemperatureSetpoint"}->coolCelsius;
						break;

				}

				$tSetTempF = $this->fahr($tSetTempC);

			} else {
				$tSetTempF = "Off";
			}

			$hvacStatus = strtolower($json->devices[0]->traits->{"sdm.devices.traits.ThermostatHvac"}->status);

			$tAmbientTempC = $json->devices[0]->traits->{"sdm.devices.traits.Temperature"}->ambientTemperatureCelsius;

			$tAmbientTempF = $this->fahr($tAmbientTempC);

			return array([
				"humidity" => $tHumidity,
				"settemp"  => $tSetTempF,
				"ambient"  => $tAmbientTempF,
				"mode"	   => $thermostatMode,
				"status"   => $hvacStatus
			]);

		} else {
			return array([
				"redir" => $this->get_perm_url
			]);
		}

	}

	public function dump() {

		$deviceurl = "devices";

		$r = $this->client->request('GET', $deviceurl);

		$json = json_decode($r->getBody());

		return $json;

	}

	private function fahr($celsius) {

		$fahr = round((($celsius * 9 / 5) + 32));

		return $fahr;
	}
}