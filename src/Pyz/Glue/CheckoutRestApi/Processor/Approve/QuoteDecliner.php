<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi\Processor\Approve;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer;
use Pyz\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class QuoteDecliner implements QuoteDeclinerInterface
{
    /**
     * @var \Pyz\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @var \Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclineMapperInterface
     */
    protected $quoteDeclineMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface
     */
    protected $checkoutRequestAttributesExpander;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface
     */
    protected $checkoutRequestValidator;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface
     */
    protected $restCheckoutErrorMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface
     */
    protected $customerMapper;

    /**
     * @param \Pyz\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclineMapperInterface $quoteDeclineMapper
     * @param \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface $checkoutRequestValidator
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface $restCheckoutErrorMapper
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface $customerMapper
     */
    public function __construct(
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteDeclineMapperInterface $quoteDeclineMapper,
        CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander,
        CheckoutRequestValidatorInterface $checkoutRequestValidator,
        RestCheckoutErrorMapperInterface $restCheckoutErrorMapper,
        CustomerMapperInterface $customerMapper
    ) {
        $this->checkoutRestApiClient = $checkoutRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteDeclineMapper = $quoteDeclineMapper;
        $this->checkoutRequestAttributesExpander = $checkoutRequestAttributesExpander;
        $this->checkoutRequestValidator = $checkoutRequestValidator;
        $this->restCheckoutErrorMapper = $restCheckoutErrorMapper;
        $this->customerMapper = $customerMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function decline(
        RestRequestInterface $restRequest,
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
    ): RestResponseInterface {
        $restCheckoutRequestAttributesTransfer = new RestCheckoutRequestAttributesTransfer();
        $restCustomerTransfer = $this->customerMapper->mapRestCustomerTransferFromRestCheckoutRequest(
            $restRequest,
            $restCheckoutRequestAttributesTransfer
        );
        $restCheckoutRequestAttributesTransfer->setCustomer($restCustomerTransfer);

        $restQuoteDeclineAttributesTransfer->setCustomer($restCustomerTransfer);

        $restQuoteDeclineAttributesTransfer->getApproverDetails()
            ->setCompanyUserId($restRequest->getRestUser()->getIdCompanyUser());

        $restQuoteDeclineResponseTransfer = $this
            ->checkoutRestApiClient
            ->declineQuote($restQuoteDeclineAttributesTransfer);

        if (!$restQuoteDeclineResponseTransfer->getIsSuccess()) {
            return $this->createQuoteDeclineErrorResponse($restQuoteDeclineResponseTransfer);
        }

        $restQuoteDeclineResponseAttributesTransfer = $this->quoteDeclineMapper
            ->mapQuoteTransferToRestQuoteDeclineResponseAttributesTransfer(
                $restQuoteDeclineResponseTransfer->getQuote(),
                $restQuoteDeclineAttributesTransfer
            );

        return $this->createRestResponse($restQuoteDeclineResponseAttributesTransfer, $restQuoteDeclineResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer $restQuoteDeclineResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer $restQuoteDeclineResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        RestQuoteDeclineResponseAttributesTransfer $restQuoteDeclineResponseAttributesTransfer,
        RestQuoteDeclineResponseTransfer $restQuoteDeclineResponseTransfer
    ): RestResponseInterface {
        $checkoutDataResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_QUOTE_DECLINE,
            null,
            $restQuoteDeclineResponseAttributesTransfer
        );

        $checkoutDataResource->setPayload($restQuoteDeclineResponseTransfer);

        $restResponse = $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($checkoutDataResource)
            ->setStatus(Response::HTTP_OK);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteDeclineResponseTransfer $restQuoteDeclineResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteDeclineErrorResponse(
        RestQuoteDeclineResponseTransfer $restQuoteDeclineResponseTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($restQuoteDeclineResponseTransfer->getErrors() as $restCheckoutErrorTransfer) {
            $restResponse->addError(
                $this->restCheckoutErrorMapper->mapRestCheckoutErrorTransferToRestErrorTransfer(
                    $restCheckoutErrorTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }
}
