<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
