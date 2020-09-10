<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface as SprykerQuoteRepositoryInterface;

interface QuoteRepositoryInterface extends SprykerQuoteRepositoryInterface
{
    /**
     * Specification:
     * - Get quote for approval collection filtered by criteria
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function filterQuoteForApprovalCollection(
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer;
}
