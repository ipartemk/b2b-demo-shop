<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi\Processor\Approve;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestApproverDetailsTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer;

class QuoteDeclineMapper implements QuoteDeclineMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer
     */
    public function mapQuoteTransferToRestQuoteDeclineResponseAttributesTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
    ): RestQuoteDeclineResponseAttributesTransfer {
        $restQuoteDeclineResponseAttributesTransfer = new RestQuoteDeclineResponseAttributesTransfer();
        $restQuoteDeclineResponseAttributesTransfer->setIdCart($restQuoteDeclineAttributesTransfer->getIdCart());

        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            $restQuoteDeclineResponseAttributesTransfer->setApproverDetails(
                (new RestApproverDetailsTransfer())
                    ->fromArray($restQuoteDeclineAttributesTransfer->getApproverDetails()->toArray(), true)
                    ->setUuidQuoteApproval($quoteApprovalTransfer->getUuid())
                    ->setUuidQuote($quoteTransfer->getUuid())
                    ->setStatus($quoteApprovalTransfer->getStatus())
            );
            // we take first approver, as we have no logic to determinate several approvers.
            // according specification, we have only 1 approver with approverDetails in response
            break;
        }
        if ($quoteTransfer->getCustomer()) {
            $restQuoteDeclineResponseAttributesTransfer->setCustomer(
                (new RestCustomerTransfer())
                    ->fromArray($quoteTransfer->getCustomer()->toArray(), true)
                    ->setUuidCompanyUser($restQuoteDeclineAttributesTransfer->getCustomer()->getUuidCompanyUser())
            );
        }

        return $restQuoteDeclineResponseAttributesTransfer;
    }
}
