<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
