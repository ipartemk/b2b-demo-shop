<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Spryker\Zed\CartCode\Business\Operation\CartCodeRemover as SprykerCartCodeRemover;

class CartCodeRemover extends SprykerCartCodeRemover
{
    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $this->executeCartCodePlugins($cartCodeRequestTransfer);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

        return $this->recalculationResultProcessor
            ->processRecalculationResults($cartCodeRequestTransfer->setQuote($quoteTransfer));
    }
}
