<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi;

use Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclineMapper;
use Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclineMapperInterface;
use Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDecliner;
use Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclinerInterface;
use Pyz\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor;
use Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutDataUpdater;
use Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutDataUpdaterInterface;
use Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutUpdateMapper;
use Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutUpdateMapperInterface;
use Pyz\Glue\CheckoutRestApi\Processor\Customer\CustomerMapper;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiFactory as SprykerCheckoutRestApiFactory;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface;

/**
 * @method \Pyz\Client\CheckoutRestApi\CheckoutRestApiClientInterface getClient()
 * @method \Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig getConfig()
 */
class CheckoutRestApiFactory extends SprykerCheckoutRestApiFactory
{
    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface
     */
    public function createCheckoutProcessor(): CheckoutProcessorInterface
    {
        return new CheckoutProcessor(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createCheckoutRequestAttributesExpander(),
            $this->createCheckoutRequestValidator(),
            $this->createRestCheckoutErrorMapper(),
            $this->createCheckoutResponseMapper()
        );
    }

    /**
     * @return \Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutDataUpdaterInterface
     */
    public function createCheckoutDataUpdater(): CheckoutDataUpdaterInterface
    {
        return new CheckoutDataUpdater(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createCheckoutUpdateMapper(),
            $this->createCheckoutRequestAttributesExpander(),
            $this->createCheckoutRequestValidator(),
            $this->createRestCheckoutErrorMapper()
        );
    }

    /**
     * @return \Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclinerInterface
     */
    public function createQuoteDecliner(): QuoteDeclinerInterface
    {
        return new QuoteDecliner(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createQuoteDeclineMapper(),
            $this->createCheckoutRequestAttributesExpander(),
            $this->createCheckoutRequestValidator(),
            $this->createRestCheckoutErrorMapper(),
            $this->createCustomerMapper()
        );
    }

    /**
     * @return \Pyz\Glue\CheckoutRestApi\Processor\CheckoutUpdate\CheckoutUpdateMapperInterface
     */
    public function createCheckoutUpdateMapper(): CheckoutUpdateMapperInterface
    {
        return new CheckoutUpdateMapper();
    }

    /**
     * @return \Pyz\Glue\CheckoutRestApi\Processor\Approve\QuoteDeclineMapperInterface
     */
    public function createQuoteDeclineMapper(): QuoteDeclineMapperInterface
    {
        return new QuoteDeclineMapper();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface
     */
    public function createCustomerMapper(): CustomerMapperInterface
    {
        return new CustomerMapper();
    }
}
