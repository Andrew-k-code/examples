<?php

namespace AndrewKode\SapIntegration\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use AndrewKode\SapIntegration\Model\Api\SapClient;
use AndrewKode\SapIntegration\Model\Config;

class OrderSync
{
    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var SapClient
     */
    protected $sapClient;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(
        CollectionFactory $orderCollectionFactory,
        SapClient $sapClient,
        Config $config
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->sapClient = $sapClient;
        $this->config = $config;
    }

    /**
     * Sync orders with SAP
     *
     * @param int|null $storeId
     * @return void
     * @throws \Exception
     */
    public function sync($storeId = null)
    {
        if (!$this->config->isSyncEnabled($storeId)) {
            return;
        }

        $statuses = $this->config->getOrderStatuses($storeId);
        
        $orders = $this->orderCollectionFactory->create()
            ->addFieldToFilter('status', ['in' => $statuses]);

        foreach ($orders as $order) {

            $payload = [
                'order_id' => $order->getIncrementId(),
                'customer_email' => $order->getCustomerEmail(),
                'total' => $order->getGrandTotal(),
                'items' => []
            ];

            foreach ($order->getAllItems() as $item) {
                $payload['items'][] = [
                    'sku' => $item->getSku(),
                    'qty' => (int)$item->getQtyOrdered(),
                    'price' => $item->getPrice()
                ];
            }

            $this->sapClient->sendOrder($payload, $storeId);

            // mark as exported (you'd normally use custom attribute)
            $order->setStatus('complete')->save();
        }
    }
}
