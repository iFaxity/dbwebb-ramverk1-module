<?php

namespace Faxity\Test;

use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;
use Anax\Commons\ContainerInjectableInterface;

/**
 * Just a wrapper so we dont need to add same code in all
 * of the controllers test classes
 */
abstract class DITestCase extends TestCase
{
    /**
     * @var $service DI service
     * @var $di Dependency injector
     */
    protected $service;
    protected $di;


    abstract protected function createService() : ContainerInjectableInterface;


    /**
     * Setup for every test case
     * @return void.
     */
    public function setUp() : void
    {
        global $di;

        // Create dependency injector with the service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $this->di = $di;
        $this->service = $this->createService();
        $this->service->setDI($di);
    }


    /**
     * Teardown for every test case
     * @return void.
     */
    public function tearDown() : void
    {
        global $di;

        $di = null;
        $this->service = null;
        $this->di = null;
    }
}
