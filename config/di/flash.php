<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "flash" => [
            "shared" => true,
            "callback" => function () {
                $cfg = $this->configuration->load("flash.php");
                $template = $cfg["config"]["template"];
                $region = $cfg["config"]["region"] ?? null;

                $flash = new \Faxity\DI\Flash($template, $region);
                $flash->setDI($this);
                $flash->render();

                return $flash;
            },
        ],
    ],
];
