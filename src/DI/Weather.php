<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Faxity\Fetch\Fetch;

/**
 * DI module for weather forecasts
 */
class Weather implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /** The base URL of ipstack's api. */
    private const DARKSKY_URL = 'https://api.darksky.net';

    /** Regex to match a coordinate */
    private const COORDS_REGEX = '/^-?\d+(\.\d+)?, ?-?\d+(\.\d+)?$/';

    /**
     * @var string $accessKey The ipstack access key.
     * @var Fetch $http The http fetch client
     */
    private $accessKey;
    private $http;


    /**
     * @param string $accessKey access key to ipstacks API
     * @param Fetch|null $fetch Fetch client (optional)
     */
    public function __construct(string $accessKey, ?Fetch $fetch = null)
    {
        $this->accessKey = $accessKey;
        $this->http = $fetch ?? new Fetch();
    }



    /**
     * Formats temperature, removes prevents -0.
     * @param float $temp Temperature in celcius
     *
     * @return string
     */
    private function formatTemp(float $temp) : string
    {
        $temp = round($temp);
        $temp = $temp == 0 ? 0 : $temp;

        return "{$temp}°C";
    }


    /**
     * Formats an item from DarkSky API, only extracting the data we want.
     * @param object $data Item holding the data
     * @param \DateTimeZone $tz Timezone of the location
     */
    private function formatWeatherItem(object $data, \DateTimeZone $tz) : object
    {
        $date = new \DateTime("@{$data->time}");
        $date->setTimezone($tz);

        return (object) [
            "date"    => $date->format("d M, Y"),
            "summary" => $data->summary ?? "",
            "icon"    => $data->icon,
            "minTemp" => $this->formatTemp($data->temperatureMin),
            "maxTemp" => $this->formatTemp($data->temperatureMax),
        ];
    }


    /**
     * Fetches data from the DarkSky API and formats it.
     * @param array $requests URLs to send requests to.
     */
    private function fetchForecast(array $requests) : array
    {
        $bodies = $this->http->getMulti($requests);

        // Merge the data of the requests
        return array_reduce($bodies, function ($acc, $body) {
            // If the exceeded its use, throw error
            if (isset($body->code)) {
                throw new \Exception($body->error);
            }

            $tz = new \DateTimeZone($body->timezone);
            $data = $body->daily->data;

            // Format each requests daily data
            $items = array_map(function ($item) use ($tz) {
                return $this->formatWeatherItem($item, $tz);
            }, $data);

            return array_merge($acc, $items);
        }, []);
    }


    public function forecast(string $location, bool $pastMonth = false) : object
    {
        if (preg_match(self::COORDS_REGEX, $location)) {
            // Split and trim user input
            $coords = array_map(function ($str) {
                return floatval(trim($str));
            }, explode(",", $location));
        } else {
            $coords = $this->di->ip->locate($location);

            if (is_null($coords)) {
                throw new \Exception("Positionen är inte en koordinat eller en ip-address.");
            }
        }

        // Unpack coordinates to variables
        list($lat, $long) = $coords;
        $url = self::DARKSKY_URL . "/forecast/{$this->accessKey}/{$lat},{$long}";
        $params = (object) [
            "lang"    => "sv",
            "units"   => "si",
            "exclude" => "currently,minutely,hourly,flags",
        ];

        if ($pastMonth) {
            $now = time();
            $requests = array_map(function ($n) use ($now, $url, $params) {
                $timestamp = $now - ($n * 24 * 60 * 60);

                return [
                    "url" => $url . ",{$timestamp}",
                    "params" => $params,
                ];
            }, range(1, 30));

            $data = $this->fetchForecast($requests);
        } else {
            $data = $this->fetchForecast([
                [ "url" => $url, "params" => $params ],
            ]);
        }

        return (object) [
            "coords" => $coords,
            "data" => $data,
        ];
    }
}
