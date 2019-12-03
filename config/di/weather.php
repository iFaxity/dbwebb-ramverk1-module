<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "weather" => [
            "shared" => true,
            "callback" => function () {
                $cfg = $this->configuration->load("weather.php");
                $apiKey = $cfg["config"]["apiKey"];

                $weather = new \Faxity\DI\Weather($apiKey);
                $weather->setDI($this);

                return $weather;
            },
        ],
    ],
];
