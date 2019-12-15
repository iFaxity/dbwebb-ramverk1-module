<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Faxity\Test\DITestCase;
use Faxity\Test\MockFetch;

/**
 * Test the Weather DI Service.
 */
class WeatherTest extends DITestCase
{
    /**
     * @var MochFetch $fetch Mock fetch client
     */
    private $fetch;


    private function generateResponse($time) : object
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
     * Creates the DI service
     */
    public function createService() : ContainerInjectableInterface
    {
        $cfg = $this->di->configuration->load("weather");
        $apiKey = $cfg["config"]["apiKey"];
        $this->fetch = new MockFetch();

        return new Weather($apiKey, $this->fetch);
    }


    public function testForecast()
    {
        $res = $this->generateResponse(time());

        $this->fetch->addResponse($res);
        $body = $this->service->forecast("-45.691, -32.14");

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
        $this->service->forecast("4.67, -87.673");
    }


    public function testForecastPastMonth()
    {
        $res = array_map(function ($n) {
            $time = strtotime("-{$n} days");
            return $this->generateResponse($time);
        }, range(1, 30));

        $this->fetch->addResponse($res);
        $body = $this->service->forecast("5.91, -25.6", true);

        $this->assertIsObject($body);
        $this->assertIsArray($body->coords);
        $this->assertCount(2, $body->coords);
        $this->assertEquals($body->coords[0], 5.91);
        $this->assertEquals($body->coords[1], -25.6);

        $this->assertIsArray($body->data);
        $this->assertCount(30, $body->data);
    }
}
