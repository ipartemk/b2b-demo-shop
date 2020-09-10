<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CartsRestApi\Business\Quote;

use Pyz\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReader as SprykerQuoteReader;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;

class QuoteReader extends SprykerQuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Pyz\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacadeBase;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface $quotePermissionChecker
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface[] $quoteCollectionExpanderPlugins
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface[] $quoteExpanderPlugins
     * @param \Pyz\Zed\Quote\Business\QuoteFacadeInterface $quoteFacadeBase
     */
    public function __construct(
        CartsRestApiToQuoteFacadeInterface $quoteFacade,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        QuotePermissionCheckerInterface $quotePermissionChecker,
        array $quoteCollectionExpanderPlugins,
        array $quoteExpanderPlugins,
        QuoteFacadeInterface $quoteFacadeBase
    ) {
        parent::__construct($quoteFacade, $storeFacade, $quotePermissionChecker, $quoteCollectionExpanderPlugins, $quoteExpanderPlugins);

        $this->quoteFacadeBase = $quoteFacadeBase;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireUuid();
        $quoteTransfer->requireCustomerReference();

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND));
        }

        $customerFromOrigQuote = clone $quoteResponseTransfer->getQuoteTransfer()->getCustomer();
        $quoteResponseTransfer->getQuoteTransfer()->setCustomer($quoteTransfer->getCustomer());

        if (!$this->quotePermissionChecker->checkQuoteReadPermission($quoteResponseTransfer->getQuoteTransfer())) {
            return $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND));
        }

        $quoteResponseTransfer->getQuoteTransfer()->setCustomer($customerFromOrigQuote);
        $expandedQuoteTransfer = $this->executeQuoteExpanderPlugins($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer->setQuoteTransfer($expandedQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteForApprovalCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $quoteCriteriaFilterTransfer->setIdStore($storeTransfer->getIdStore());

        $quoteCollectionTransfer = $this->quoteFacadeBase->getQuoteForApprovalCollection($quoteCriteriaFilterTransfer);

        return $this->executeQuoteCollectionExpanderPlugins(
            $quoteCriteriaFilterTransfer,
            $quoteCollectionTransfer
        );
    }
}
