<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\PersistentCart\Business\Model;

use Spryker\Zed\PersistentCart\Business\Model\QuoteResolver as SprykerQuoteResolver;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface;
use Spryker\Zed\PersistentCart\PersistentCartConfig;

class QuoteResolver extends SprykerQuoteResolver
{
    /**
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findCustomerQuoteById(int $idQuote, CustomerTransfer $customerTransfer): ?QuoteTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        if (!$quoteResponseTransfer->getIsSuccessful() || !$this->isQuoteReadAllowed($quoteTransfer, $customerTransfer)
        ) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue(static::GLOSSARY_KEY_QUOTE_NOT_AVAILABLE);
            $this->messengerFacade->addErrorMessage($messageTransfer);

            return null;
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteReadAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        $isAllowed = $customerTransfer->getCustomerReference() === $quoteTransfer->getCustomerReference()
            || $this->isAnonymousCustomerQuote($quoteTransfer->getCustomerReference())
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('ReadSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            );

        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if (!$quoteApprovalTransfer->getApprover()
                || !$quoteApprovalTransfer->getApprover()->getCustomer()
                || !$quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference()
            ) {
                continue;
            }
            //we allow approver to manage Quote
            if ($quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference() === $customerTransfer->getCustomerReference()) {
                $isAllowed = true;

                break;
            }
        }

        return $isAllowed;
    }
}
