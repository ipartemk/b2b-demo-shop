<?php

namespace Pyz\Zed\ByDesign\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
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
