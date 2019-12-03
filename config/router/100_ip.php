<?php
/**
 * Loads the routes for the IP controllers.
 */
return [
    "routes" => [
        [
            "info" => "Controller for the IP API",
            "mount" => "ip-api",
            "handler" => "\Faxity\IP\APIController",
        ],
        [
            "info" => "Controller for the IP pages",
            "mount" => "ip",
            "handler" => "\Faxity\IP\Controller",
        ],
    ],
];
