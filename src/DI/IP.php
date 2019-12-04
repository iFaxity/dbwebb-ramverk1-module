<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Faxity\Fetch\Fetch;

/**
 * DI module for validating & geo-locating IP addresses
 */
class IP implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /** The base URL of ipstack's api. */
    private const IPSTACK_URL = "http://api.ipstack.com";

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
     * Gets the IP address of the client
     *
     * @return string
     */
    public function getAddress() : string
    {
        return $_SERVER["REMOTE_ADDR"] ?? "";
    }


    /**
     * Locates an ip address
     * @param string|null $ip IP address to locate
     *
     * @return array|null
     */
    public function locate(?string $ip) : ?array
    {
        // Check if ip is valid and not empty
        if (filter_var($ip, FILTER_VALIDATE_IP) == $ip) {
            $params = [ "access_key" => $this->accessKey ];
            $res = $this->http->get(self::IPSTACK_URL . "/$ip", $params);

            if (isset($res->latitude, $res->longitude)) {
                return [ $res->latitude, $res->longitude ];
            }
        }

        return null;
    }


    /**
     * Validate IP address and get info about the domain, null is returned if empty
     * @param string|null $ip IP to Validate
     *
     * @return object|null
     */
    public function validate(?string $ip) : ?object
    {
        if (empty($ip)) {
            return null;
        }

        $data = (object) [
            "ip" => $ip,
            "valid" => false,
            "type" => null,
            "domain" => null,
            "region" => null,
            "country" => null,
            "location" => null,
        ];

        // Check which protocol the address uses
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $data->type = "ipv4";
            $data->valid = true;
        } else if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $data->type = "ipv6";
            $data->valid = true;
        }

        if ($data->valid) {
            $res = $this->http->get(self::IPSTACK_URL . "/$ip", [
                "access_key" => $this->accessKey,
            ]);
            $host = gethostbyaddr($ip);
            $data->domain = $host != $ip ? $host : null;
            $data->region = $res->region_name ?? null;
            $data->country = $res->country_name ?? null;

            if (isset($res->latitude, $res->longitude)) {
                $data->location = (object) [
                    "latitude" => $res->latitude,
                    "longitude" => $res->longitude,
                ];
            }
        }

        return $data;
    }
}
