<?php

namespace App\Notifier;

use GuzzleHttp\Client as GuzzleClient;
use Twilio\Rest\Client As TwilioClient;

class Notifier
{
	public static function sms($smsto, $message) {
		$account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sender = getenv("TWILIO_SENDER_SID");
        $twilio_number = getenv("TWILIO_NUMBER");

    	$twilio = new TwilioClient($account_sid, $token);

	    $message = $twilio->messages
			->create($smsto,
		        array(
		          "from" => $twilio_number,
		          "body" => $message
		        )
	    );

      	return($message->sid);

	}

	public static function push($trigger, $message = null, $body = true) {

        $ifttt_key = env('IFTTT_KEY');

        $url = "https://maker.ifttt.com/trigger/{$trigger}/json/with/key/{$ifttt_key}";

        if ($body) {

        	$client = new GuzzleClient([
            	'headers' => [ 'Content-Type' => 'application/json' ]
        	]);

        	$data = ['message' => $message];

       		$response = $client->post($url, ['body' => json_encode($data)]);
       	
       	} else {
       	
       		$client = new GuzzleClient();
       		$response = $client->request("POST", $url);
       	
       	}

        if( $response->getStatusCode() < 400) {
        	return true;
        } else return false;

	}

}