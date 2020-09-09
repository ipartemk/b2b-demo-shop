<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestApproverDetailsTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\RestPointOfContactTransfer;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapper as SprykerCartMapper;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestCartsDiscountsTransfer;
use Generated\Shared\Transfer\RestCartsTotalsTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartMapper extends SprykerCartMapper implements CartMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartsAttributesTransfer
     */
    public function mapQuoteTransferToRestCartsAttributesTransfer(QuoteTransfer $quoteTransfer): RestCartsAttributesTransfer
    {
        $restCartsAttributesTransfer = parent::mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer);
        $this->setCustomer($quoteTransfer, $restCartsAttributesTransfer);
        $this->setPointOfContact($quoteTransfer, $restCartsAttributesTransfer);
        $this->setApproverDetails($quoteTransfer, $restCartsAttributesTransfer);

        return $restCartsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setCustomer(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): void {
        if ($quoteTransfer->getCustomer()) {
            $restCartsAttributesTransfer->setCustomer(
                (new RestCustomerTransfer())
                    ->fromArray($quoteTransfer->getCustomer()->toArray(), true)
//                    ->setUuidCompanyUser($restCheckoutRequestAttributesTransfer->getCustomer()->getUuidCompanyUser())
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setPointOfContact(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): void {
        if ($quoteTransfer->getPointOfContact()) {
            $restCartsAttributesTransfer->setPointOfContact(
                (new RestPointOfContactTransfer())->fromArray($quoteTransfer->getPointOfContact()->toArray(), true)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setApproverDetails(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): void {
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            $restApproverDetailsTransfer = new RestApproverDetailsTransfer();
            if ($quoteApprovalTransfer->getApproverDetails()) {
                $restApproverDetailsTransfer
                    ->fromArray($quoteApprovalTransfer->getApproverDetails()->toArray(), true);
            }
            $restApproverDetailsTransfer
                ->setUuidQuoteApproval($quoteApprovalTransfer->getUuid())
                ->setStatus($quoteApprovalTransfer->getStatus());
            $restCartsAttributesTransfer->setApproverDetails($restApproverDetailsTransfer);
            // we take first approver, as we have no logic to determinate several approvers.
            // according specification, we have only 1 approver with approverDetails in response
            break;
        }
    }
}
