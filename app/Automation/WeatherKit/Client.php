<?php

namespace App\Automation\WeatherKit;

use DateTimeZone;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected const BASE_URL = 'https://weatherkit.apple.com/api/v1/';
    protected string $token;
    protected ?float $latitude;
    protected ?float $longitude;
    protected DateTimeZone $timezone;

    public function __construct(string $token, string $timezone = 'America/Chicago') {

        $this->token = $token;

        $this->timezone = new DateTimeZone(timezone: $timezone);

        $this->client = new GuzzleClient(['base_uri' => self::BASE_URL]);
    }

    function getConditions($latitude, $longitude) {

        $endpoint = "weather/en-US/{$latitude}/{$longitude}";

        $dataset  = "currentWeather,forecastDaily";

        $data = $this->request($endpoint, $dataset);

        $conditions['cloudCover'] = $data['currentWeather']['cloudCover'];
        $conditions['tempFahr'] = round(( $data['currentWeather']['temperature'] * 1.8 ) + 32, 1);
        $conditions['condition'] = strtolower($data['currentWeather']['conditionCode']);
        $conditions['humidity'] = $data['currentWeather']['humidity'];
        $conditions['precipChance'] = $data['forecastDaily']['days'][0]['precipitationChance'];

        return $conditions;

    }

    function request($endpoint, $datasets) {

        $response = $this->client->request('GET', $endpoint, [
            'query' => [
                'countryCode' => 'us',
                'dataSets' => $datasets,
                'timezone' => $this->timezone->getName()
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Encoding' => 'gzip',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);

        // If response is not 200 (Success),
        // then return an empty array.
        if ($response->getStatusCode() != 200) {
            return [];
        }

        // Extract body from response.
        $body = (string) $response->getBody();

        return json_decode(json: $body, associative: true);
    }
}
