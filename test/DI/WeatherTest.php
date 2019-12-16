<?php

namespace Faxity\DI;

use Faxity\Test\DITestCase;
use Faxity\Test\MockFetch;

/**
 * Test the Weather DI Service.
 */
class WeatherTest extends DITestCase
{
    /** @var MochFetch $fetch Mock fetch client */
    private $fetch;

    /** @var Weather $weather IP service */
    private $weather;


    /**
     * Generates a DarkSky forecast response
     * @param int $time
     *
     * @return object
     */
    private function generateResponse(int $time) : object
    {
        return (object) [
            "timezone" => "Europe/Stockholm",
            "daily" => (object) [
                "data" => [
                    (object) [
                        "time" => $time,
                        "summary" => "Weather looks fine today",
                        "icon" => "test-icon",
                        "temperatureMin" => rand(1, 100) / 10,
                        "temperatureMax" => rand(50, 150) / 10,
                    ],
                ],
            ],
        ];
    }


    /**
     * Setup for each test case
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $cfg = $this->di->configuration->load("weather");
        $apiKey = $cfg["config"]["apiKey"];

        $this->fetch = new MockFetch();
        $this->weather = new Weather($apiKey, $this->fetch);
        $this->weather->setDI($this->di);
    }


    /**
     * Teardown for each test case
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->weather = null;
    }


    public function testForecast()
    {
        $res = $this->generateResponse(time());

        $this->fetch->addResponse($res);
        $body = $this->weather->forecast("-45.691, -32.14");

        $this->assertIsObject($body);
        $this->assertIsArray($body->coords);
        $this->assertCount(2, $body->coords);
        $this->assertEquals($body->coords[0], -45.691);
        $this->assertEquals($body->coords[1], -32.14);

        $this->assertIsArray($body->data);
        $this->assertCount(1, $body->data);
    }


    public function testForecastFail()
    {
        $this->expectException(\Exception::class);

        $res = (object) [
            "code" => 403,
            "error" => "Too many requests",
        ];

        $this->fetch->addResponse($res);
        $this->weather->forecast("4.67, -87.673");
    }


    public function testForecastPastMonth()
    {
        $res = array_map(function ($n) {
            $time = strtotime("-{$n} days");
            return $this->generateResponse($time);
        }, range(1, 30));

        $this->fetch->addResponse($res);
        $body = $this->weather->forecast("5.91, -25.6", true);

        $this->assertIsObject($body);
        $this->assertIsArray($body->coords);
        $this->assertCount(2, $body->coords);
        $this->assertEquals($body->coords[0], 5.91);
        $this->assertEquals($body->coords[1], -25.6);

        $this->assertIsArray($body->data);
        $this->assertCount(30, $body->data);
    }
}
