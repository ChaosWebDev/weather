<?php

namespace Chaoswd\Weather;

use GuzzleHttp\Client;

class Weather
{
    public $alerts;
    public $daily;
    public $hourly;

    protected $headers;
    public $urls = [];

    private $client;


    public function __construct(string $userAgent, public $lat, public $long)
    {
        $this->client = new Client();

        $this->headers = [
            'headers' => [
                "User-Agent: {$userAgent}",
                'Accept: application/geo+json'
            ]
        ];

        $this->urls['base'] = "https://api.weather.gov";
        $this->urls['alerts'] = "{$this->urls['base']}/alerts/active?point={$this->lat},{$this->long}";

        $this->getURLs();
        $this->getAlerts();
    }

    protected function getURLs()
    {
        $response = $this->client->get("{$this->urls['base']}/points/{$this->lat},{$this->long}", $this->headers);

        $res = json_decode($response->getBody(), true);

        $this->urls['forecast'] = $res['properties']['forecast'];
        $this->urls['hourly'] = $res['properties']['forecastHourly'];
    }

    public function getAlerts()
    {
        if ($this->urls['alerts'] == "" || $this->urls['alerts'] == null) {
            $this->getURLs();
        }

        $response = $this->client->get($this->urls['alerts'], $this->headers);
        $res = json_decode($response->getBody(), true);
        $this->alerts = $res['features'][0]['properties'] ?? [];

        return $this;
    }

    public function getDailyForecast()
    {
        if ($this->urls['alerts'] == "" || $this->urls['alerts'] == null) {
            $this->getURLs();
        }

        $response = $this->client->get($this->urls['forecast'], $this->headers);
        $res = json_decode($response->getBody(), true);
        $this->daily = $res['properties']['periods'];

        return $this;
    }

    public function getHourlyForecast()
    {

        if ($this->urls['alerts'] == "" || $this->urls['alerts'] == null) {
            $this->getURLs();
        }

        $response = $this->client->get($this->urls['hourly'], $this->headers);
        $res = json_decode($response->getBody(), true);
        $this->hourly = $res['properties']['periods'];

        return $this;
    }
}