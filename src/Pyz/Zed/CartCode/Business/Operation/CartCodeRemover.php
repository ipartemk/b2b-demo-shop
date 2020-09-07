<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CartCode\Business\Operation;

use Spryker\Zed\CartCode\Business\Operation\CartCodeRemover as SprykerCartCodeRemover;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToCalculationFacadeInterface;

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
