<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutUpdateResponseTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface as SpykerCheckoutRestApiClientInterface;

interface CheckoutRestApiClientInterface extends SpykerCheckoutRestApiClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Provides user checkout data based on data passed in RestCheckoutRequestAttributesTransfer.
     * - Checkout data will include stored Quote.
     * - Recalculates quote.
     * - Saves quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutUpdateResponseTransfer
     */
    public function updateCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutUpdateResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Decline Quote by approval.
     * - Saves quote.
     *
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer
     */
    public function declineQuote(
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineRequestAttributesTransfer
    ): RestQuoteDeclineResponseTransfer;
}
