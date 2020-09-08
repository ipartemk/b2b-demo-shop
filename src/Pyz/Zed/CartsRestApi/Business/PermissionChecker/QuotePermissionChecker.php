<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi\Business\PermissionChecker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker as SprykerQuotePermissionChecker;

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
