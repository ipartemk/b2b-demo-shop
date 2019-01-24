<?php

namespace Pyz\Zed\ByDesign\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

interface ApiAdapterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ByDesignResponseTransfer
     */
    public function create(OrderTransfer $orderTransfer);
}
