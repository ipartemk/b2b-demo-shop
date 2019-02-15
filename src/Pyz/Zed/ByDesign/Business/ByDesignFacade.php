<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ByDesign\Business\ByDesignBusinessFactory getFactory()
 */
class ByDesignFacade extends AbstractFacade implements ByDesignFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function postOrder(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createOrderCreator()
            ->createOrder($orderTransfer);
    }
}
