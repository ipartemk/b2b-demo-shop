<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi\Processor\Checkout;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutUpdateResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutUpdateResponseTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor as SprykerCheckoutProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutProcessor extends SprykerCheckoutProcessor
{
    /**
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface $checkoutRequestValidator
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface $restCheckoutErrorMapper
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapperInterface $checkoutResponseMapper
     */
    public function __construct(
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander,
        CheckoutRequestValidatorInterface $checkoutRequestValidator,
        RestCheckoutErrorMapperInterface $restCheckoutErrorMapper,
        CheckoutResponseMapperInterface $checkoutResponseMapper
    ) {
        parent::__construct(
            $checkoutRestApiClient,
            $restResourceBuilder,
            $checkoutRequestAttributesExpander,
            $checkoutRequestValidator,
            $restCheckoutErrorMapper,
            $checkoutResponseMapper
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestResponseInterface {
        $restErrorCollectionTransfer = $this->checkoutRequestValidator->validateCheckoutRequest($restRequest, $restCheckoutRequestAttributesTransfer);
        if ($restErrorCollectionTransfer->getRestErrors()->count()) {
            return $this->createValidationErrorResponse($restErrorCollectionTransfer);
        }

        $restCheckoutRequestAttributesTransfer = $this->checkoutRequestAttributesExpander
            ->expandCheckoutRequestAttributes($restRequest, $restCheckoutRequestAttributesTransfer);

        $restCheckoutResponseTransfer = $this->checkoutRestApiClient->placeOrder($restCheckoutRequestAttributesTransfer);
        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderFailedErrorResponse($restCheckoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        return $this->createOrderPlacedResponse($restCheckoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createOrderPlacedResponse(
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer
    ): RestResponseInterface {
        $restCheckoutResponseAttributesTransfer = $this->checkoutResponseMapper
            ->mapRestCheckoutResponseTransferToRestCheckoutResponseAttributesTransfer(
                $restCheckoutResponseTransfer,
                new RestCheckoutResponseAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            $restCheckoutResponseTransfer->getOrderReference(),
            $restCheckoutResponseAttributesTransfer
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($restResource);
    }
}
