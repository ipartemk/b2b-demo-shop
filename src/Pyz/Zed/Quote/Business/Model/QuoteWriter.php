<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Business\Model\QuoteWriter as SprykerQuoteWriter;

class QuoteWriter extends SprykerQuoteWriter
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function update(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteByIdTransfer = $this->quoteRepository->findQuoteById($quoteTransfer->getIdQuote());
        if (!$quoteByIdTransfer) {
            return $this->createQuoteResponseTransfer($quoteTransfer);
        }

        $quoteValidationResponseTransfer = $this->quoteValidator->validate($quoteTransfer);

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseTransfer($quoteTransfer)
                ->setErrors($quoteValidationResponseTransfer->getErrors());
        }

        $quoteTransfer = $this->reloadStoreForQuote($quoteTransfer);

        if ($quoteTransfer->getCustomerReference() !== $quoteTransfer->getCustomer()->getCustomerReference()) {
            //we allow approver to manage Quote
            //check if current Customer is approver
            foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
                if (!$quoteApprovalTransfer->getApprover()
                    || !$quoteApprovalTransfer->getApprover()->getCustomer()
                    || !$quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference()
                ) {
                    continue;
                }
                if ($quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference()) {
                    $quoteTransfer->setCustomer($quoteByIdTransfer->getCustomer());

                    break;
                }
            }
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            return $this->executeUpdateTransaction($quoteTransfer);
        });
    }
}
