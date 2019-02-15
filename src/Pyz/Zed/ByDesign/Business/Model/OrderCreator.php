<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Business\Model;

use Generated\Shared\Transfer\ByDesignResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Pyz\Zed\ByDesign\Business\Api\Adapter\ApiAdapterInterface;
use Pyz\Zed\ByDesign\Business\Logger\ByDesignLoggerInterface;
use Pyz\Zed\ByDesign\ByDesignConfig;

class OrderCreator implements OrderCreatorInterface
{
    /**
     * @var \Pyz\Zed\ByDesign\Business\Api\Adapter\ApiAdapterInterface
     */
    protected $apiAdapter;

    /**
     * @var \Pyz\Zed\ByDesign\ByDesignConfig
     */
    protected $config;

    /**
     * @var \Pyz\Zed\ByDesign\Business\Logger\ByDesignLoggerInterface
     */
    protected $logger;

    /**
     * @param \Pyz\Zed\ByDesign\Business\Api\Adapter\ApiAdapterInterface $apiAdapter
     * @param \Pyz\Zed\ByDesign\ByDesignConfig $config
     * @param \Pyz\Zed\ByDesign\Business\Logger\ByDesignLoggerInterface $logger
     */
    public function __construct(
        ApiAdapterInterface $apiAdapter,
        ByDesignConfig $config,
        ByDesignLoggerInterface $logger
    ) {
        $this->apiAdapter = $apiAdapter;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrder(OrderTransfer $orderTransfer)
    {
        $this->logger->info('Creating Order: ', $orderTransfer);
        $byDesignResponseTransfer = $this->apiAdapter->create($orderTransfer);

        $this->logger->info('ByDesign Response: ', $byDesignResponseTransfer);
        $this->persistByDesignData($orderTransfer, $byDesignResponseTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ByDesignResponseTransfer $byDesignResponseTransfer
     *
     * @return void
     */
    protected function persistByDesignData(
        OrderTransfer $orderTransfer,
        ByDesignResponseTransfer $byDesignResponseTransfer
    ) {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity */
        $salesOrderEntity = SpySalesOrderQuery::create()
            ->findOneByIdSalesOrder($orderTransfer->getIdSalesOrder());

        $salesOrderEntity->setIdSalesOrderByDesign($byDesignResponseTransfer->getSalesOrderId());
        $salesOrderEntity->save();
    }
}
