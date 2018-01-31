<?php

namespace Bc\App\Services\SendGridService;

use Exception;

/*
 * This is a really super lightweight version with limited options.
 * Use the provided PHP library from SendGrid for more robust solutions.
 *
 */

class SendGridService
{
    public $apiKey;

    public function __construct($apiKey = '')
    {
        $this->setApiKey($apiKey);
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendRequest($url, $params, $method, $json = true)
    {
        try {

            $response = $this->curlCall($url, json_encode($params), $method, $json, [
                'Authorization' => 'Bearer ' . $this->apiKey
            ]);

            if ($response != 202) {
                throw new Exception('SendGrid Rejected the request: ' . $response, 500);
            }

        } catch (Exception $ex) {
            return [
                'success' => false,
                'message' => 'Failed to send to sengrid',
                'error_code' => $ex->getCode(),
                'error_message' => $ex->getMessage(),
            ];
        }

        return $response;
    }

    protected function curlCall(
        $url,
        $params = [],
        $method = 'GET',
        $json = false,
        $headers = []
    ) {
        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        if (!empty($headers) && is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(
                function($name, $value) {
                    return "$name: $value";
                },
                array_keys($headers), $headers)
            );
        }

        // Check for params
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        // Enforce Method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // grab URL and pass it to the browser
        $data = curl_exec($ch);

        if (empty($data)) {
            $data = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        // close cURL resource, and free up system resources
        curl_close($ch);

        return $data;
    }
}
