<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\PlaceOrderProcessor as SprykerPlaceOrderProcessor;

class PlaceOrderProcessor extends SprykerPlaceOrderProcessor
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer
    {
        $checkoutResponseTransfer = $this->validateCheckoutData($restCheckoutRequestAttributesTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);

        $restCheckoutResponseTransfer = $this->validateQuoteTransfer($quoteTransfer);
        if ($restCheckoutResponseTransfer !== null) {
            return $restCheckoutResponseTransfer;
        }

        $quoteTransfer = $this->mapRestCheckoutRequestAttributesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $quoteTransfer = $this->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->executePlaceOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteResponseTransfer = $this->deleteQuote($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseError(
                $quoteResponseTransfer,
                CheckoutRestApiConfig::ERROR_IDENTIFIER_UNABLE_TO_DELETE_CART
            );
        }

        return $this->createRestCheckoutResponseTransferWithQuote($checkoutResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createRestCheckoutResponseTransferWithQuote(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): RestCheckoutResponseTransfer {
        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(true)
            ->setRedirectUrl($checkoutResponseTransfer->getRedirectUrl())
            ->setIsExternalRedirect($checkoutResponseTransfer->getIsExternalRedirect())
            ->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference())
            ->setCheckoutResponse($checkoutResponseTransfer)
            ->setQuote($quoteTransfer);
    }
}
