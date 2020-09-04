<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CheckoutRestApi\Business\Checkout;

use Spryker\Zed\CheckoutRestApi\Business\Checkout\PlaceOrderProcessor as SprykerPlaceOrderProcessor;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;

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
