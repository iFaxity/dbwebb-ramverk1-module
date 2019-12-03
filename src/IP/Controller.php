<?php

namespace Faxity\IP;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Controller for the /ip routes
 */
class Controller implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /** List of IP addresses to have available as test */
    private const TEST_ADDRS = [
        "2001:db8::1",
        "2a03:2880:f00a:8:face:b00c:0:2",
        "1200::ABYX:1234::2552:7777:1313",
        "194.47.150.9",
        "192.168.1.20",
        "83.230.116.044",
    ];

    /** API response example for a failed response */
    private const API_EXAMPLE_ERR = [
        "message" => "Ingen IP address skickades.",
    ];

    /** API response example for a successfull response */
    private const API_EXAMPLE_OK = [
        "ip" => "194.47.150.9",
        "valid" => true,
        "domain" => "dbwebb.se",
        "type" => "ipv4",
        "region" => "Blekinge",
        "country" => "Sweden",
        "location" => [
            "latitude"  => 56.16122055053711,
            "longitude" => 15.586899757385254,
        ],
    ];

    /**
     * @var object $examples API response examples
     */
    private $examples;


    /**
     * Initializer for the class
     */
    public function initialize()
    {
        $this->examples = (object) [
            "err" => json_encode(self::API_EXAMPLE_ERR, JSON_PRETTY_PRINT),
            "ok" => json_encode(self::API_EXAMPLE_OK, JSON_PRETTY_PRINT),
        ];
    }


    /**
     * Handles / for the controller
     *
     * @return object
     */
    public function indexActionGet() : object
    {
        // Deal with the action and return a response.
        $ip = $this->di->request->getGet("ip");

        if (empty($ip)) {
            $ip = $this->di->ip->getAddress();
        }

        $res = (array) $this->di->ip->validate($ip);

        $this->di->page->add("faxity/ip/index", $res);
        $this->di->page->add("faxity/ip/api", [
            "ip"       => $ip,
            "addrs"    => $this::TEST_ADDRS,
            "apiUrl"   => $this->di->url->create("ip-api"),
            "examples" => $this->examples,
        ]);

        return $this->di->page->render([
            "title" => "IP validerare",
        ]);
    }
}
