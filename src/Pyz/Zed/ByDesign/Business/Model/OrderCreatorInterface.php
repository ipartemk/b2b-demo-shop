<?php

namespace Pyz\Zed\ByDesign\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrder(OrderTransfer $orderTransfer);
}
