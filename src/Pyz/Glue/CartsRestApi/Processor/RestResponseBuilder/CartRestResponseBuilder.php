<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilder as SprykerCartRestResponseBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CartRestResponseBuilder extends SprykerCartRestResponseBuilder implements CartRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartApprovalRestResponse(QuoteTransfer $quoteTransfer, string $localeName): RestResponseInterface
    {
        return $this->createRestResponse()->addResource(
            $this->createCartApprovalResourceWithItems($quoteTransfer, $localeName)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCartApprovalResourceWithItems(QuoteTransfer $quoteTransfer, string $localeName): RestResourceInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_CARTS_APPROVAL,
            $quoteTransfer->getUuid(),
            $this->cartMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        $cartResource->setPayload($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->addCartItemRelationships($cartResource, $itemTransfer, $localeName);
        }

        return $cartResource;
    }
}
