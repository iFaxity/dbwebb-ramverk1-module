<?php

namespace Faxity\IP;

use Faxity\Test\ControllerTestCase;

/**
 * Test the IPv6 Controller.
 */
class APIControllerTest extends ControllerTestCase
{
    protected $className = APIController::class;


    /**
     * Test the route "index".
     */
    public function testIndexAction() : void
    {
        $this->di->request->setPost("ip", "2002:c0a8:101::42");

        $res = $this->controller->indexActionPost();
        $this->assertInternalType("array", $res);

        list($json, $status) = $res;
        $this->assertEquals($json["valid"], true);
        $this->assertEquals($status, 200);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionFail() : void
    {
        $this->di->request->setPost("ip", "1200:0000:AB00:1234:O000:2552:7777:1313");

        $res = $this->controller->indexActionPost();
        $this->assertInternalType("array", $res);

        list($json, $status) = $res;
        $this->assertEquals($json["valid"], false);
        $this->assertEquals($status, 200);
    }


    /**
     * Test the route "index".
     */
    public function testIndexActionError() : void
    {
        $res = $this->controller->indexActionPost();
        $this->assertInternalType("array", $res);

        list($json, $status) = $res;
        $this->assertEquals($json["message"], "Ingen IP address skickades.");
        $this->assertEquals($status, 400);
    }
}
