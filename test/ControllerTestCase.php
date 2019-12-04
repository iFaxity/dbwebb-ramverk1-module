<?php

namespace Test;

use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Just a wrapper so we dont need to add same code in all
 * of the controllers test classes
 */
class ControllerTestCase extends TestCase
{
    /**
     * @var $controller Anax Controller class
     * @var $di Dependency injector
     * @var $className Controller class name
     */
    protected $controller;
    protected $di;
    protected $className;

    /**
     * Setup for every test case
     * @return void.
     */
    public function setUp() : void
    {
        global $di;

        // Create dependency injector with the controller
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $controllerClass = $this->className;
        $controller = new $controllerClass();
        $controller->setDI($di);

        if (method_exists($controller, "initialize")) {
            $controller->initialize();
        }

        $this->di = $di;
        $this->controller = $controller;
    }

    /**
     * Teardown for every test case
     * @return void.
     */
    public function tearDown() : void
    {
        global $di;

        $di = null;
        $this->controller = null;
        $this->di = null;
    }
}
