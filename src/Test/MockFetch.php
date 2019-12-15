<?php

namespace Faxity\Test;

use Faxity\Fetch\Fetch;

/**
 * Send HTTP requests via cURL
 */
class MockFetch extends Fetch
{
    private $queue = [];


    /**
     * Adds a response to the queue, to add multiple set an array instead
     * @param object|array $response
     */
    public function addResponse($response)
    {
        if (is_object($response)) {
            $this->queue[] = $response;
        } else if (is_array($response)) {
            $this->queue = array_merge($this->queue, $response);
        }
    }


    /**
     * Gets teh first response in the queue.
     * @param string $url URL to send request to
     * @param mixed $params Optional query parameters as an array or object
     *
     * @return object
     */
    public function get(string $url, $params = null) : ?object
    {
        if (empty($this->queue)) {
            throw new \Exception("No responses in the queue.");
        }

        return array_shift($this->queue);
    }


    /**
     * Gets multiple requests from start of the queue.
     * @param array $requests An array of assoc-arrays with url and params as keys, like:
     * [
     *   "url" => "",
     *   "params" => [ "param" => "" ]
     * ]
     */
    public function getMulti(array $requests)
    {
        $len = count($requests);

        if (count($this->queue) < $len) {
            throw new \Exception("Not enough responses in the queue.");
        }

        return array_splice($this->queue, 0, $len);
    }
}
