<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "ip" => [
            "shared" => true,
            "callback" => function () {
                $cfg = $this->configuration->load("ip.php");
                $apiKey = $cfg["config"]["apiKey"];

                $ip = new \Faxity\DI\IP($apiKey);
                $ip->setDI($this);

                return $ip;
            },
        ],
    ],
];
