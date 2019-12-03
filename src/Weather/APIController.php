<?php

namespace Faxity\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Controller for the /ip-api routes
 */
class APIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * Handles / for the controller
     *
     * @return array
     */
    public function indexActionPost() : array
    {
        $location = $this->di->request->getPost("location", "");
        $pastMonth = $this->di->request->getPost("past-month") !== null;
        $status = 200;

        try {
            $res = $this->di->weather->forecast($location, $pastMonth);
        } catch (\Exception $ex) {
            $status = 400;
            $message = $ex->getMessage();
            $res = [ "status" => $status, "message" => $message ];
        }

        return [ (array) $res, $status ];
    }
}
