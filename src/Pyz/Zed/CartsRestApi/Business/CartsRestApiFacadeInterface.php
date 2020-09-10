<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi\Business;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface as SprykerCartsRestApiFacadeInterface;

interface CartsRestApiFacadeInterface extends SprykerCartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Retrieves customer quote collection filtered by criteria and by QuoteApproval.idCompanyUser.
     * - Filters by customer reference when provided.
     * - Filters by current store ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteForApprovalCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer;
}
