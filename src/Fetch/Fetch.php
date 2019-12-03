<?php

namespace Faxity\Fetch;

/**
 * Send HTTP requests via cURL
 */
class Fetch
{
    /**
     * Initializes a curl request
     * @param string $url URL to send request to
     * @param mixed $params Optional query parameters as an array or object
     *
     * @return resource|bool
     */
    private function initRequest(string $url, $params)
    {
        if ($params) {
            $query = "";

            if (is_object($params) || is_array($params)) {
                $query = http_build_query($params);
                $url .= $query ? "?$query" : "";
            }
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }


    /**
     * Sends a GET request to URL
     * @param string $url URL to send request to
     * @param mixed $params Optional query parameters as an array or object
     *
     * @return object
     */
    public function get(string $url, $params = null) : ?object
    {
        $curl = self::initRequest($url, $params);

        $body = curl_exec($curl);
        curl_close($curl);

        // Parse response as json
        return json_decode($body);
    }


    /**
     * Sends multiple GET requests in parallell
     * @param array $requests An array of assoc-arrays with url and params as keys, like:
     * [
     *   "url" => "",
     *   "params" => [ "param" => "" ]
     * ]
     */
    public function getMulti(array $requests)
    {
        $multi = curl_multi_init();

        // Create handles for cURL request
        $handles = array_map(function ($item) use ($multi) {
            $handle = self::initRequest($item["url"], $item["params"]);
            curl_multi_add_handle($multi, $handle);

            return $handle;
        }, $requests);

        // Execute requests in parallell and wait for all to complete
        do {
            $status = curl_multi_exec($multi, $active);
            $active && curl_multi_select($multi); // Prevents CPU overusage
        } while ($active && $status == CURLM_OK);


        // Cleanup and get content
        $content = array_map(function ($handle) use ($multi) {
            curl_multi_remove_handle($multi, $handle);

            $body = curl_multi_getcontent($handle);
            return json_decode($body);
        }, $handles);

        curl_multi_close($multi);

        return $content;
    }
}
