<?php
/**
 * Loads the routes for the Weather controllers.
 */
return [
    "routes" => [
        [
            "info" => "Controller for the Weather API",
            "mount" => "weather-api",
            "handler" => "\Faxity\Weather\APIController",
        ],
        [
            "info" => "Controller for the Weather pages",
            "mount" => "weather",
            "handler" => "\Faxity\Weather\Controller",
        ],
    ],
];
