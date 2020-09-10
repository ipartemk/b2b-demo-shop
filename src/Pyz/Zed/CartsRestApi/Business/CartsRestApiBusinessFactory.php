<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi\Business;

use Pyz\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
use Pyz\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Pyz\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory as SprykerCartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Pyz\Zed\CartsRestApi\Business\Quote\QuoteReader;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiBusinessFactory extends SprykerCartsRestApiBusinessFactory
{
    /**
     * @return \Pyz\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getQuoteFacade(),
            $this->getStoreFacade(),
            $this->createQuotePermissionChecker(),
            $this->getQuoteCollectionExpanderPlugins(),
            $this->getQuoteExpanderPlugins(),
            $this->getQuoteBaseFacade()
        );
    }

    /**
     * @return \Pyz\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    public function createQuotePermissionChecker(): QuotePermissionCheckerInterface
    {
        return new QuotePermissionChecker();
    }

    /**
     * @return \Pyz\Zed\Quote\Business\QuoteFacadeInterface
     */
    public function getQuoteBaseFacade(): QuoteFacadeInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::FACADE_QUOTE_BASE);
    }
}
