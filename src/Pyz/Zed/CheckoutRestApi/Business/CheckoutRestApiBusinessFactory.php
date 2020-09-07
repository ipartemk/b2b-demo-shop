<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi\Business;

use Pyz\Zed\CheckoutRestApi\Business\Checkout\CheckoutDataWriter;
use Pyz\Zed\CheckoutRestApi\Business\Checkout\CheckoutDataWriterInterface;
use Pyz\Zed\CheckoutRestApi\Business\Checkout\PlaceOrderProcessor;
use Pyz\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReader;
use Pyz\Zed\CheckoutRestApi\CheckoutRestApiDependencyProvider;
use Pyz\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\PlaceOrderProcessorInterface;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory as SprykerCheckoutRestApiBusinessFactory;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface;

/**
 * @method \Spryker\Zed\CheckoutRestApi\CheckoutRestApiConfig getConfig()
 */
class CheckoutRestApiBusinessFactory extends SprykerCheckoutRestApiBusinessFactory
{
    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\Checkout\PlaceOrderProcessorInterface
     */
    public function createPlaceOrderProcessor(): PlaceOrderProcessorInterface
    {
        return new PlaceOrderProcessor(
            $this->createQuoteReader(),
            $this->getCartFacade(),
            $this->getCheckoutFacade(),
            $this->getQuoteFacade(),
            $this->getCalculationFacade(),
            $this->getQuoteMapperPlugins(),
            $this->getCheckoutDataValidatorPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\CheckoutRestApi\Business\Checkout\CheckoutDataWriterInterface
     */
    public function createCheckoutDataWriter(): CheckoutDataWriterInterface
    {
        return new CheckoutDataWriter(
            $this->createQuoteReader(),
            $this->getQuoteMapperPlugins(),
            $this->getCalculationFacade(),
            $this->getBaseQuoteFacade(),
            $this->getQuoteApprovalFacade(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getCartsRestApiFacade());
    }

    /**
     * @return \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    public function getBaseQuoteFacade(): QuoteFacadeInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::FACADE_QUOTE_BASE);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    public function getQuoteApprovalFacade(): QuoteApprovalFacadeInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::FACADE_QUOTE_APPROVAL);
    }

    /**
     * @return \Pyz\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::FACADE_COMPANY_USER);
    }
}