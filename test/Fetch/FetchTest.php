<?php

namespace Faxity\Fetch;

use PHPUnit\Framework\TestCase;

/**
 * Test the IPv6 Controller.
 */
class FetchTest extends TestCase
{
    const API_URL = "https://reqres.in/api";

    /**
     * Test get method
     */
    public function testGet() : void
    {
        $fetch = new Fetch();
        $body = $fetch->get(self::API_URL . "/users");

        $this->assertIsObject($body);
        $this->assertEquals($body->page, 1);
        $this->assertIsArray($body->data);

        foreach ($body->data as $item) {
            $this->assertIsInt($item->id);
            $this->assertIsString($item->email);
        }
    }


    /**
     * Test get method with parameters
     */
    public function testGetWithParams() : void
    {
        $fetch = new Fetch();
        $params = [ "page" => 2 ];
        $body = $fetch->get(self::API_URL . "/users", $params);

        $this->assertIsObject($body);
        $this->assertEquals($body->page, 2);
        $this->assertIsArray($body->data);

        foreach ($body->data as $item) {
            $this->assertIsInt($item->id);
            $this->assertIsString($item->email);
        }
    }


    /**
     * Test getMulti method
     */
    public function testGetMulti() : void
    {
        $fetch = new Fetch();
        $url = self::API_URL . "/users/";
        $requests = array_map(function ($n) use ($url) {
            return [ "url" => $url . $n ];
        }, range(1, 5));

        $bodies = $fetch->getMulti($requests);

        foreach ($bodies as $body) {
            $this->assertIsObject($body);
            $this->assertIsObject($body->data);
            $this->assertIsInt($body->data->id);
            $this->assertIsString($body->data->email);
        }
    }
}
