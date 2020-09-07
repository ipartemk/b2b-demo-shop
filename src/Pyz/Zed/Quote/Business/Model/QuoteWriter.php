<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Quote\Business\Model;

use Spryker\Zed\Quote\Business\Model\QuoteWriter as SprykerQuoteWriter;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfiguratorInterface;
use Spryker\Zed\Quote\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

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
