<?php

namespace AndrewKode\SapIntegration\Cron;

use AndrewKode\SapIntegration\Model\OrderSync;
use Psr\Log\LoggerInterface;

class SyncOrders
{
    /**
     * @var OrderSync
     */
    protected $orderSync;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(OrderSync $orderSync, LoggerInterface $logger)
    {
        $this->orderSync = $orderSync;
        $this->logger = $logger;
    }

    /**
     * Execute the cron job
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->logger->info('Starting SAP order sync cron job');
            $this->orderSync->sync();
            $this->logger->info('SAP order sync completed successfully');
        } catch (\Exception $e) {
            $this->logger->error('Error syncing orders to SAP: ' . $e->getMessage());
        }
    }
}
