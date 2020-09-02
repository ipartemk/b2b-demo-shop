<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CheckoutRestApi\Business\Checkout\Quote;

use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReader as SprykerQuoteReader;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;

class QuoteReader extends SprykerQuoteReader
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findCustomerQuoteByUuid(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        if (
            !$restCheckoutRequestAttributesTransfer->getCustomer()
            || !$restCheckoutRequestAttributesTransfer->getCustomer()->getCustomerReference()
        ) {
            return null;
        }

        $quoteTransfer = $this->createQuoteTransfer($restCheckoutRequestAttributesTransfer);

        $quoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $customerReference = $restCheckoutRequestAttributesTransfer->getCustomer()->getCustomerReference();
        if ($quoteTransfer->getCustomerReference() !== $customerReference) {
            $permission = false;
            //we allow approver to manage Quote
            //check if current Customer is approver
            foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
                if (!$quoteApprovalTransfer->getApprover()
                    || !$quoteApprovalTransfer->getApprover()->getCustomer()
                    || !$quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference()
                ) {
                    continue;
                }
                if ($quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference() === $customerReference) {
                    $permission = true;
                }
            }

            if (!$permission) {
                return null;
            }
        }

        if (!$quoteTransfer->getCustomer()) {
            $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
            $quoteTransfer->setCustomer($customerTransfer);
        }

        return $quoteTransfer;
    }
}
