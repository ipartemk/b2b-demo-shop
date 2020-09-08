<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomersRestApi\Business\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapper as SprykerCustomerQuoteMapper;

class CustomerQuoteMapper extends SprykerCustomerQuoteMapper implements CustomerQuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCustomerToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $customerReference = $quoteTransfer->getCustomerReference();
        $restCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCustomer();
        $hasRestCustomerReference = ($restCustomerTransfer && $restCustomerTransfer->getCustomerReference() !== null);

        if ($customerReference === null
            && !$hasRestCustomerReference
        ) {
            return $quoteTransfer;
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerReference);

        if (!$customerResponseTransfer->getHasCustomer()) {
            if (!$hasRestCustomerReference) {
                return $quoteTransfer;
            }

            $customerTransfer = (new CustomerTransfer())
                ->fromArray($restCustomerTransfer->toArray(), true)
                ->setIsGuest(true);

            return $quoteTransfer
                ->setCustomer($customerTransfer)
                ->setCustomerReference($customerTransfer->getCustomerReference());
        }

        $quoteTransfer
            ->setCustomerReference($customerResponseTransfer->getCustomerTransfer()->getCustomerReference())
            ->setCustomer($customerResponseTransfer->getCustomerTransfer());

        return $quoteTransfer;
    }
}
