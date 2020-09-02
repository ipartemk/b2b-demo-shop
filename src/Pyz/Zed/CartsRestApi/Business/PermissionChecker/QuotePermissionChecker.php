<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CartsRestApi\Business\PermissionChecker;

use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker as SprykerQuotePermissionChecker;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class QuotePermissionChecker extends SprykerQuotePermissionChecker implements QuotePermissionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function checkQuotePermission(QuoteTransfer $quoteTransfer, string $permissionPluginKey): bool
    {
        $quoteTransfer->requireIdQuote();

        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if ($quoteTransfer->getCustomer()->getCustomerReference() === $quoteTransfer->getCustomerReference()) {
            return true;
        }

        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            if (!$quoteApprovalTransfer->getApprover()
                || !$quoteApprovalTransfer->getApprover()->getCustomer()
                || !$quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference()
            ) {
                continue;
            }
            //we allow approver to manage Quote
            if ($quoteApprovalTransfer->getApprover()->getCustomer()->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference()) {
                return true;
            }
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()
            || !$quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()) {
            return false;
        }

        return $this->can(
            $permissionPluginKey,
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(),
            $quoteTransfer->getIdQuote()
        );
    }
}
