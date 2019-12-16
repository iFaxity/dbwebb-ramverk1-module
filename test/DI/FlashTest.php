<?php

namespace Faxity\DI;

use Faxity\Test\DITestCase;

/**
 * Test the Flash DI Service.
 */
class FlashTest extends DITestCase
{
    /**
     * @var Flash $flash
     */
    private $flash;


    /**
     * Setup for each test case
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->flash = new Flash("faxity/flash/default", "flash");
        $this->flash->setDI($this->di);
    }


    /**
     * Teardown for each test case
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->flash = null;
    }


    /**
     * Tests the ok, warn and err methods, for adding messages
     */
    public function testAddMessage() : void
    {
        $this->flash->ok("Success message");
        $this->flash->warn("Warning message");
        $this->flash->err("Error message");

        $messages = $this->flash->getMessages();
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
        $this->flash->render();

        $this->assertTrue($this->di->view->hasContent("flash"));
    }
}
