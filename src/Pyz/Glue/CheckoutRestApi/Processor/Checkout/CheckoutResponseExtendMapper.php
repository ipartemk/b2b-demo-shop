<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\CheckoutRestApi\Processor\Checkout;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestApproverDetailsTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Generated\Shared\Transfer\RestPointOfContactTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapper as SprykerCheckoutResponseMapper;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutResponseMapperPluginInterface;

class CheckoutResponseExtendMapper implements CheckoutResponseMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer
     */
    public function mapRestCheckoutResponseTransferToRestCheckoutResponseAttributesTransfer(
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer,
        RestCheckoutResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
    ): RestCheckoutResponseAttributesTransfer {
        //@artem @todo
        $tt = 111;
        $quoteTransfer = $restCheckoutResponseTransfer->getQuote();
        if ($quoteTransfer->getCustomer()) {
            $restCheckoutResponseAttributesTransfer->setCustomer(
                (new RestCustomerTransfer())
                    ->fromArray($quoteTransfer->getCustomer()->toArray(), true)
                // @artem
//                    ->setUuidCompanyUser($restCheckoutRequestAttributesTransfer->getCustomer()->getUuidCompanyUser())
            );
        }
        if ($quoteTransfer->getBillingAddress()) {
            $restCheckoutResponseAttributesTransfer->setBillingAddress(
                (new RestAddressTransfer())->fromArray($quoteTransfer->getBillingAddress()->toArray(), true)
            );
        }
        if ($quoteTransfer->getShippingAddress()) {
            $restCheckoutResponseAttributesTransfer->setShippingAddress(
                (new RestAddressTransfer())->fromArray($quoteTransfer->getShippingAddress()->toArray(), true)
            );
        }
        if ($quoteTransfer->getPayment()) {
            $restCheckoutResponseAttributesTransfer->addPayment(
                (new RestPaymentTransfer())
                    ->setPaymentProviderName($quoteTransfer->getPayment()->getPaymentProvider())
                    ->setPaymentMethodName($quoteTransfer->getPayment()->getPaymentMethod())
                    ->setPaymentSelection($quoteTransfer->getPayment()->getPaymentSelection())
            );
        }
        if ($quoteTransfer->getShipment() && $quoteTransfer->getShipment()->getMethod()) {
            $restCheckoutResponseAttributesTransfer->setShipment(
                (new RestShipmentTransfer())
                    ->setIdShipmentMethod($quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod())
            );
        }
        if ($quoteTransfer->getPointOfContact()) {
            $restCheckoutResponseAttributesTransfer->setPointOfContact(
                (new RestPointOfContactTransfer())->fromArray($quoteTransfer->getPointOfContact()->toArray(), true)
            );
        }
        foreach ($quoteTransfer->getQuoteApprovals() as $quoteApprovalTransfer) {
            $restCheckoutResponseAttributesTransfer->setApproverDetails(
                (new RestApproverDetailsTransfer())
                    ->fromArray($quoteApprovalTransfer->getApproverDetails()->toArray(), true)
                    ->setUuidQuoteApproval($quoteApprovalTransfer->getUuid())
                    ->setUuidQuote($quoteTransfer->getUuid())
                    ->setStatus($quoteApprovalTransfer->getStatus())
            );
            // we take first approver, as we have no logic to determinate several approvers.
            // according specification, we have only 1 approver with approverDetails in response
            break;
        }

        return $restCheckoutResponseAttributesTransfer;
    }
}
