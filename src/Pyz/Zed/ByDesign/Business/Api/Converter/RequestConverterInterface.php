<?php

namespace Pyz\Zed\ByDesign\Business\Api\Converter;

use Generated\Shared\Transfer\OrderTransfer;

interface RequestConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function convert(OrderTransfer $orderTransfer);
}
