<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Quote\Business\Model;

use Spryker\Zed\Quote\Business\Model\QuoteReader as SprykerQuoteReader;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

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
