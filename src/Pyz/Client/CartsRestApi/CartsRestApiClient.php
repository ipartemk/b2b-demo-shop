<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CartsRestApi;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClient as SprykerCartsRestApiClient;

/**
 * @method \Pyz\Client\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsRestApiClient extends SprykerCartsRestApiClient implements CartsRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteForApprovalCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->getQuoteForApprovalCollection($quoteCriteriaFilterTransfer);
    }
}
