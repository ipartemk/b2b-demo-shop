<?php

namespace Pyz\Zed\ByDesign\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface ByDesignFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function postOrder(OrderTransfer $orderTransfer);
}
