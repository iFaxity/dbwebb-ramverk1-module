<?php

namespace Faxity\DI;

use Faxity\Test\DITestCase;
use Faxity\Test\MockFetch;

/**
 * Test the IP DI Service.
 */
class IPTest extends DITestCase
{
    /** @var MockFetch $fetch Fetch client to mock responses */
    private $fetch;

    /** @var IP $ip IP service */
    private $ip;


    /**
     * Setup for each test case
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $cfg = $this->di->configuration->load("ip");
        $apiKey = $cfg["config"]["apiKey"];

        $this->fetch = new MockFetch();
        $this->ip = new IP($apiKey, $this->fetch);
        $this->ip->setDI($this->di);
    }


    /**
     * Teardown for each test case
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->ip = null;
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
        $coords = $this->ip->locate("194.47.150.9");

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
        $coords = $this->ip->locate("someip");

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
        $data = $this->ip->validate("194.47.150.9");

        $this->assertEquals("194.47.150.9", $data->ip);
        $this->assertTrue($data->valid);
        $this->assertEquals("ipv4", $data->type);
        $this->assertStringStartsWith("dbwebb.", $data->domain);
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
        $data = $this->ip->validate("2001:db8::1");

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
        $data = $this->ip->validate("");

        $this->assertNull($data);
    }


    /**
     * Test getAddress method
     */
    public function testGetAddress()
    {
        $_SERVER["REMOTE_ADDR"] = "10.20.30.40";
        $addr = $this->ip->getAddress();

        $this->assertEquals($addr, "10.20.30.40");
    }
}
