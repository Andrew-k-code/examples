<?php

namespace AndrewKode\SapIntegration\Model\Api;

use Magento\Framework\HTTP\Client\Curl;
use AndrewKode\SapIntegration\Model\Config;

class SapClient
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Curl $curl, Config $config)
    {
        $this->curl = $curl;
        $this->config = $config;
    }

    /**
     * Send order data to SAP
     *
     * @param array $orderData
     * @param int|null $storeId
     * @return array
     * @throws \Exception
     */
    public function sendOrder(array $orderData, $storeId = null)
    {
        if (!$this->config->isEnabled($storeId)) {
            throw new \Exception("SAP Integration is not enabled");
        }

        try {
            $apiUrl = $this->config->getApiUrl($storeId);
            $username = $this->config->getUsername($storeId);
            $password = $this->config->getPassword($storeId);
            $apiKey = $this->config->getApiKey($storeId);

            if (!$apiUrl || !$username || !$password || !$apiKey) {
                throw new \Exception("SAP credentials are not properly configured");
            }

            // Reset curl for new request
            $this->curl = new Curl();

            // Add headers
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Authorization", "Basic " . base64_encode($username . ":" . $password));
            $this->curl->addHeader("X-API-Key", $apiKey);

            // Send request
            $this->curl->post($apiUrl, json_encode($orderData));

            $response = $this->curl->getBody();
            $status = $this->curl->getStatus();

            if ($status != 200 && $status != 201) {
                throw new \Exception("SAP API error (Status: $status): " . $response);
            }

            return json_decode($response, true);

        } catch (\Exception $e) {
            throw new \Exception("SAP Integration failed: " . $e->getMessage());
        }
    }
}
