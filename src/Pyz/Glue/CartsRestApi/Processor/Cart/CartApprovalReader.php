<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\CartsRestApi\CartsRestApiClientInterface;
use Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartApprovalReader implements CartApprovalReaderInterface
{
    /**
     * @var \Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @var \Pyz\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $customerExpanderPlugins;

    /**
     * @param \Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Pyz\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $customerExpanderPlugins
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient,
        array $customerExpanderPlugins
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->customerExpanderPlugins = $customerExpanderPlugins;
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteApprovalByUuid(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
        $customerTransfer = $this->executeCustomerExpanderPlugins($customerTransfer, $restRequest);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setCustomer($customerTransfer)
            ->setUuid($uuidCart);

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
        }

        return $this->cartRestResponseBuilder->createCartApprovalRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAllQuoteApprovals(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteCollectionTransfer = $this->getQuotesForApproval($restRequest);

        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $this->cartRestResponseBuilder->createRestResponse();
        }

        return $this->cartRestResponseBuilder->createRestQuoteCollectionResponse($quoteCollectionTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function getQuotesForApproval(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = $this->cartsRestApiClient->getQuoteForApprovalCollection(
            (new QuoteCriteriaFilterTransfer())
                ->setQuoteApprovalIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser())
        );

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerExpanderPlugins(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer
    {
        foreach ($this->customerExpanderPlugins as $customerExpanderPlugin) {
            $customerTransfer = $customerExpanderPlugin->expand($customerTransfer, $restRequest);
        }

        return $customerTransfer;
    }
}
