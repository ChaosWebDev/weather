# ChaosWD Weather Library

A lightweight PHP library for interacting with the National Weather Service (weather.gov) API.  
Provides alerts, daily forecasts, and hourly forecasts using a simple OOP interface.

---

## Installation

```
composer require chaoswd/weather
```

---

## Usage

```php
use Chaoswd\Weather\Weather;

$weather = new Weather(
    userAgent: 'YourAppName (your-email@example.com)',
    lat: '47.6062',
    long: '-122.3321'
);

// Get alerts
$weather->getAlerts();
print_r($weather->alerts);

// Get daily forecast
$weather->getDailyForecast();
print_r($weather->daily);

// Get hourly forecast
$weather->getHourlyForecast();
print_r($weather->hourly);

// OR Chain methods
$weather->getAlerts()->getDailyForecast()->getHourlyForecast();
print_r($weather->daily);
```
---

## Finding Your Latitude & Longitude

Use this tool (or any you prefer) to convert a U.S. ZIP code into latitude and longitude:  
https://www.freemaptools.com/convert-us-zip-code-to-lat-lng.htm

---

## API Notes

- All requests are sent to **https://api.weather.gov**
- A proper **User-Agent** string is required by the NWS API.  
  Format recommendation:  
  `YourAppName (contact@example.com)`
- Review their documentation at **https://www.weather.gov/documentation/services-web-api**

---

## Features

- Fetch active weather alerts
- Fetch full daily forecast
- Fetch hourly forecast
- Auto‑builds required forecast URLs using `/points/{lat},{long}` endpoint
- Clean, chainable OOP interface

---

## Requirements

- PHP 8.3+
- Composer
- guzzlehttp/guzzle

---

## License

Licensed under the MIT license. See [LICENSE](LICENSE) for details.
