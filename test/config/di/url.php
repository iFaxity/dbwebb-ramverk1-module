<?php
/**
 * Configuration file for DI container.
 */
return [
    "services" => [
        "url" => [
            "shared" => true,
            "callback" => function () {
                $url = new \Anax\Url\Url();

                $cfg = $this->configuration->load("url");

                $request = $this->get("request");
                $url->setSiteUrl($request->getSiteUrl());
                $url->setBaseUrl($request->getBaseUrl());
                $url->setStaticSiteUrl($request->getSiteUrl());
                $url->setStaticBaseUrl($request->getBaseUrl());
                $url->setScriptName($request->getScriptName());
                $url->configure($cfg["config"]);
                $url->setDefaultsFromConfiguration();
                return $url;
            }
        ],
    ],
];
