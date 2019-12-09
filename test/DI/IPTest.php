<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Test\DITestCase;
use Test\MockFetch;

/**
 * Test the IP DI Service.
 */
class IPTest extends DITestCase
{
    /**
     * @var MockFetch $fetch Fetch client to mock responses
     */
    private $fetch;


    /**
     * Creates the DI service
     */
    public function createService() : ContainerInjectableInterface
    {
        $cfg = $this->di->configuration->load("ip");
        $apiKey = $cfg["config"]["apiKey"];
        $this->fetch = new MockFetch();

        return new IP($apiKey, $this->fetch);
    }


    /**
     * Test locate method
     */
    public function testLocate()
    {
        $res = (object) [
            "latitude" => 35.461,
            "longitude" => 10.164,
        ];

        $this->fetch->addResponse($res);
        $coords = $this->service->locate("194.47.150.9");

        $this->assertIsArray($coords);
        $this->assertCount(2, $coords);
        $this->assertEquals($coords[0], 35.461);
        $this->assertEquals($coords[1], 10.164);
    }


    /**
     * Test locate method
     */
    public function testLocateFail()
    {
        $coords = $this->service->locate("someip");

        $this->assertNull($coords);
    }


    /**
     * Test validate method
     */
    public function testValidateIPV4()
    {
        $res = (object) [
            "ip" => "194.47.129.122",
            "valid" => true,
            "type" => "ipv4",
            "region_name" => "Blekinge",
            "country_name" => "Sweden",
            "latitude" => 56.16122055053711,
            "longitude" => 15.586899757385254,
        ];

        $this->fetch->addResponse($res);
        $data = $this->service->validate("194.47.150.9");

        $this->assertEquals("194.47.150.9", $data->ip);
        $this->assertTrue($data->valid);
        $this->assertEquals("ipv4", $data->type);
        $this->assertEquals($data->domain, "dbwebb.se");
        $this->assertIsString($data->region);
        $this->assertIsString($data->country);
        $this->assertIsObject($data->location);
    }


    /**
     * Test validate method
     */
    public function testValidateIPV6()
    {
        $res = (object) [
            "ip" => "2001:db8::1",
            "valid" => true,
            "type" => "ipv6",
            "region_name" => null,
            "country_name" => null,
            "latitude" => null,
            "longitude" => null,
        ];

        $this->fetch->addResponse($res);
        $data = $this->service->validate("2001:db8::1");

        $this->assertEquals("2001:db8::1", $data->ip);
        $this->assertTrue($data->valid);
        $this->assertEquals("ipv6", $data->type);
        // Not a real ip, the data below should be null
        $this->assertNull($data->domain);
        $this->assertNull($data->region);
        $this->assertNull($data->country);
        $this->assertNull($data->location);
    }


    /**
     * Test validate method
     */
    public function testValidateFail()
    {
        $data = $this->service->validate("");

        $this->assertNull($data);
    }


    /**
     * Test getAddress method
     */
    public function testGetAddress()
    {
        $_SERVER["REMOTE_ADDR"] = "10.20.30.40";
        $addr = $this->service->getAddress();

        $this->assertEquals($addr, "10.20.30.40");
    }
}
