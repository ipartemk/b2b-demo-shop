<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Zed\Quote\Business\Model\QuoteReader as SprykerQuoteReader;

class QuoteReader extends SprykerQuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Pyz\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getFilteredQuoteForApprovalCollection(
        QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
    ): QuoteCollectionTransfer {
        $quoteCollectionTransfer = $this->quoteRepository->filterQuoteForApprovalCollection($quoteCriteriaFilterTransfer);
        $quoteCollectionTransfer = $this->executeExpandQuotePluginsForQuoteCollection($quoteCollectionTransfer);

        return $quoteCollectionTransfer;
    }
}