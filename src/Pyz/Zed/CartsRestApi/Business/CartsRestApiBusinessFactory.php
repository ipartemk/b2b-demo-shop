<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CartsRestApi\Business;

use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory as SprykerCartsRestApiBusinessFactory;
use Pyz\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapper;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdder;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteMerger;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteMergerInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReader;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdater;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUpdaterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreator;
use Spryker\Zed\CartsRestApi\Business\Quote\SingleQuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\GuestQuoteItemAdder;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\GuestQuoteItemAdderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapper;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdder;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemDeleter;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemDeleterInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReader;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemUpdater;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemUpdaterInterface;
use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiBusinessFactory extends SprykerCartsRestApiBusinessFactory
{
    /**
     * @return \Pyz\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface
     */
    public function createQuotePermissionChecker(): QuotePermissionCheckerInterface
    {
        return new QuotePermissionChecker();
    }
}
