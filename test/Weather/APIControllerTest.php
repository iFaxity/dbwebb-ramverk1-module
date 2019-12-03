<?php

namespace Faxity\Weather;

use Test\ControllerTestCase;

/**
 * Test the Weather API Controller.
 */
class APIControllerTest extends ControllerTestCase
{
    protected $className = APIController::class;


    /**
     * Test the route "index".
     */
    public function testIndexAction() : void
    {
        $this->di->request->setPost("location", "67.17, -43.984");

        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);

        list($body, $status) = $res;
        $this->assertEquals($status, 200);
        $this->assertIsArray($body);

        $this->assertIsArray($body["coords"]);
        $this->assertCount(2, $body["coords"]);
        $this->assertEquals($body["coords"][0], 67.17);
        $this->assertEquals($body["coords"][1], -43.984);

        $this->assertIsArray($body["data"]);
        $this->assertCount(8, $body["data"]);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionFail() : void
    {
        $this->di->request->setPost("location", "");

        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);

        list($body, $status) = $res;
        $this->assertEquals($status, 400);
        $this->assertIsString($body["message"]);
    }
}
