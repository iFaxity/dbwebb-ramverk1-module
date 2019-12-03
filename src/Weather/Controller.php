<?php

namespace Faxity\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Controller for the /ip routes
 */
class Controller implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /** API response example for a failed response */
    private const API_EXAMPLE_ERR = [
        "status" => 400,
        "message" => "Positionen är inte en koordinat eller en ip-address",
    ];

    /** API response example for a successfull response */
    private const API_EXAMPLE_OK = [
        "coords" => [ 56.1747726, 15.5694792 ],
        "data" => [
            [
                "date" => "01 Dec, 2019",
                "summary" => "Möjligtvis lätta regnskurar över natten.",
                "icon" => "partly-cloudy-day",
                "minTemp" => "-7°C",
                "maxTemp" => "4°C",
            ],
            [
                "date" => "02 Dec, 2019",
                "summary" => "Regnskurar på morgonen.",
                "icon" => "rain",
                "minTemp" => "-1°C",
                "maxTemp" => "4°C",
            ],
            [
                "date" => "03 Dec, 2019",
                "summary" => "Regnskurar under eftermiddagen och kvällen.",
                "icon" => "rain",
                "minTemp" => "-2°C",
                "maxTemp" => "8°C",
            ],
            [
                "date" => "04 Dec, 2019",
                "summary" => "Mulet under dagen.",
                "icon" => "cloudy",
                "minTemp" => "5°C",
                "maxTemp" => "7°C",
            ],
            [
                "date" => "05 Dec, 2019",
                "summary" => "Möjligtvis lite duggregn över natten.",
                "icon" => "cloudy",
                "minTemp" => "6°C",
                "maxTemp" => "7°C",
            ],
            [
                "date" => "06 Dec, 2019",
                "summary" => "Regnskurar under dagen.",
                "icon" => "rain",
                "minTemp" => "7°C",
                "maxTemp" => "9°C",
            ],
            [
                "date" => "07 Dec, 2019",
                "summary" => "Möjligtvis lite duggregn och hård vind på morgonen.",
                "icon" => "rain",
                "minTemp" => "4°C",
                "maxTemp" => "9°C",
            ],
            [
                "date" => "08 Dec, 2019",
                "summary" => "Regnskurar fram till kvällen.",
                "icon" => "rain",
                "minTemp" => "3°C",
                "maxTemp" => "7°C",
            ],
        ],
    ];


    /**
     * Creates a border box for OpenStreetMap
     * @param array $coords Coordinates [ $lat, $long ]
     *
     * @return string
     */
    private function createMapBorderBox(array $coords) : string
    {
        list($lat, $long) = $coords;
        $offset = 0.02;

        $bbox = [
            $long - $offset,
            $lat - $offset,
            $long + $offset,
            $lat + $offset,
        ];
        return implode(",", $bbox);
    }


    /**
     * Handles / for the controller
     *
     * @return object
     */
    public function indexActionGet() : object
    {
        // Deal with the action and return a response.
        $location = $this->di->request->getGet("location");
        $pastMonth = $this->di->request->getGet("past-month") !== null;

        try {
            if (!is_null($location)) {
                $res = $this->di->weather->forecast($location, $pastMonth);
            }
        } catch (\Exception $ex) {
            $this->di->flash->err($ex->getMessage());
        }

        // Set Data needed in the render.
        $this->di->page->add("faxity/weather/index", [
            "res"      => $res ?? null,
            "apiUrl"   => $this->di->url->create("weather-api"),
            "location" => $location,
            "bbox"     => isset($res) ? $this->createMapBorderBox($res->coords) : "",
            "coords"   => isset($res) ? implode(",", $res->coords) : "",
        ]);

        return $this->di->page->render([
            "title" => "Väderkollen",
        ]);
    }


    /**
     * Handles /docs for the controller
     *
     * @return object
     */
    public function docsActionGet() : object
    {
        // Set data needed in the render.
        $this->di->page->add("faxity/weather/docs", [
            "apiUrl"   => $this->di->url->create("weather-api"),
            "examples" => (object) [
                "ok"  => json_encode(self::API_EXAMPLE_OK, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                "err" => json_encode(self::API_EXAMPLE_ERR, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ],
        ]);

        return $this->di->page->render([
            "title" => "Väderkollen API",
        ]);
    }
}
