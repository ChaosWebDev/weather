<?php

namespace Chaoswd\Weather;

class Weather
{
    public $alerts;
    public $daily;
    public $hourly;

    protected $headers;
    public $urls = [];

    public function __construct(string $userAgent, public $lat, public $long)
    {
        $this->headers = [
            "User-Agent: {$userAgent}",
            'Accept: application/geo+json'
        ];

        $this->urls['base'] = "https://api.weather.gov";
        $this->urls['alerts'] = "{$this->urls['base']}/alerts/active?point={$this->lat},{$this->long}";

        $this->getURLs();
        $this->getAlerts();
    }

    /** ------------------------------------------------------------------
     *  INTERNAL CURL WRAPPER
     * ------------------------------------------------------------------*/
    protected function get(string $url): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        // For PHP 8.2-^8.3
        // Depreciated in 8.4
        curl_close($curl);

        if ($err) {
            throw new \Exception("CURL Error requesting {$url}: {$err}");
        }

        return json_decode($response, true);
    }

    /** ------------------------------------------------------------------
     *  Fetch forecast URLs
     * ------------------------------------------------------------------*/
    protected function getURLs()
    {
        $res = $this->get("{$this->urls['base']}/points/{$this->lat},{$this->long}");

        $this->urls['forecast'] = $res['properties']['forecast'] ?? null;
        $this->urls['hourly'] = $res['properties']['forecastHourly'] ?? null;
    }

    /** ------------------------------------------------------------------
     *  Alerts
     * ------------------------------------------------------------------*/
    public function getAlerts()
    {
        if (empty($this->urls['alerts'])) {
            $this->getURLs();
        }

        $res = $this->get($this->urls['alerts']);
        $this->alerts = $res['features'][0]['properties'] ?? [];

        return $this;
    }

    /** ------------------------------------------------------------------
     *  Daily Forecast
     * ------------------------------------------------------------------*/
    public function getDailyForecast()
    {
        if (empty($this->urls['forecast'])) {
            $this->getURLs();
        }

        $res = $this->get($this->urls['forecast']);
        $this->daily = $res['properties']['periods'] ?? [];

        return $this;
    }

    /** ------------------------------------------------------------------
     *  Hourly Forecast
     * ------------------------------------------------------------------*/
    public function getHourlyForecast()
    {
        if (empty($this->urls['hourly'])) {
            $this->getURLs();
        }

        $res = $this->get($this->urls['hourly']);
        $this->hourly = $res['properties']['periods'] ?? [];

        return $this;
    }
}
