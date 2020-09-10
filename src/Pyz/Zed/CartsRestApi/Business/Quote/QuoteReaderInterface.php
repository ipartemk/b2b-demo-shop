<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface as SprykerQuoteReaderInterface;

interface QuoteReaderInterface extends SprykerQuoteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteForApprovalCollection(
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer;
}
