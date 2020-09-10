<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote\Business;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface as SprykerQuoteFacadeInterface;

interface QuoteFacadeInterface extends SprykerQuoteFacadeInterface
{
    /**
     * Specification:
     * - Gets quote collection filtered by criteria and by QuoteApproval.idCompanyUser
     * - Filters by FilterTransfer when provided.
     * - Filters by customer reference when provided.
     * - Filters by store ID when provided.
     * - Executes quote QuoteExpanderPluginInterface plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteForApprovalCollection(
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer;
}
