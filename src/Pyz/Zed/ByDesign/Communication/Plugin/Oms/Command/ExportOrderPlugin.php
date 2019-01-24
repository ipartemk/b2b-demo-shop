<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ByDesign\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\ByDesign\Business\ByDesignFacadeInterface getFacade()
 * @method \Pyz\Zed\ByDesign\Communication\ByDesignCommunicationFactory getFactory()
 * @method \Pyz\Zed\ByDesign\ByDesignConfig getConfig()
 */
class ExportOrderPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        $this->getFacade()->postOrder($orderTransfer);
    }
}
