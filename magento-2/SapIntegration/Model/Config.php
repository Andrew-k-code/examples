<?php

namespace AndrewKode\SapIntegration\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_ENABLED = 'sapintegration/general/enabled';
    const XML_PATH_API_URL = 'sapintegration/general/api_url';
    const XML_PATH_USERNAME = 'sapintegration/credentials/username';
    const XML_PATH_PASSWORD = 'sapintegration/credentials/password';
    const XML_PATH_API_KEY = 'sapintegration/credentials/api_key';
    const XML_PATH_SYNC_ENABLED = 'sapintegration/sync_settings/sync_enabled';
    const XML_PATH_SYNC_SCHEDULE = 'sapintegration/sync_settings/sync_schedule';
    const XML_PATH_ORDER_STATUS = 'sapintegration/sync_settings/order_status';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if SAP integration is enabled
     */
    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get SAP API URL
     */
    public function getApiUrl($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_API_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get SAP Username
     */
    public function getUsername($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_USERNAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get SAP Password
     */
    public function getPassword($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_PASSWORD,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get SAP API Key
     */
    public function getApiKey($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if order sync is enabled
     */
    public function isSyncEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SYNC_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get sync schedule (cron expression)
     */
    public function getSyncSchedule($storeId = null): string
    {
        $schedule = $this->scopeConfig->getValue(
            self::XML_PATH_SYNC_SCHEDULE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        return $schedule ?: '*/5 * * * *';
    }

    /**
     * Get order statuses to sync
     */
    public function getOrderStatuses($storeId = null): array
    {
        $statuses = $this->scopeConfig->getValue(
            self::XML_PATH_ORDER_STATUS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        
        if (is_string($statuses)) {
            return explode(',', $statuses);
        }
        
        return (array)$statuses ?: ['processing'];
    }
}
