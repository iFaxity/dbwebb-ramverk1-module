<?php

namespace Faxity\DI;

use Anax\Commons\ContainerInjectableInterface;
use Test\DITestCase;

/**
 * Test the Flash DI Service.
 */
class FlashTest extends DITestCase
{
    /**
     * Creates the DI service
     */
    public function createService() : ContainerInjectableInterface
    {
        return new Flash("faxity/flash/default", "flash");
    }


    /**
     * Tests the ok, warn and err methods, for adding messages
     */
    public function testAddMessage() : void
    {
        $this->service->ok("Success message");
        $this->service->warn("Warning message");
        $this->service->err("Error message");

        $messages = $this->service->getMessages();
        $this->assertIsArray($messages);
        $this->assertCount(3, $messages);

        list($ok, $warn, $err) = $messages;

        $this->assertEquals($ok->type, "ok");
        $this->assertEquals($ok->text, "Success message");
        $this->assertEquals($warn->type, "warn");
        $this->assertEquals($warn->text, "Warning message");
        $this->assertEquals($err->type, "err");
        $this->assertEquals($err->text, "Error message");
    }


    /**
     * Tests the render method
     */
    public function testRender()
    {
        $this->assertFalse($this->di->view->hasContent("flash"));

        // Check if render adds region to view
        $this->service->render();

        $this->assertTrue($this->di->view->hasContent("flash"));
    }
}
