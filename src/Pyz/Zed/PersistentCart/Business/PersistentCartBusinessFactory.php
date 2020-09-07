<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\PersistentCart\Business;

use Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory as SprykerPersistentCartBusinessFactory;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLocker;
use Spryker\Zed\PersistentCart\Business\Locker\QuoteLockerInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpander;
use Spryker\Zed\PersistentCart\Business\Model\CartChangeRequestExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\CartOperation;
use Spryker\Zed\PersistentCart\Business\Model\CartOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteDeleterInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperation;
use Spryker\Zed\PersistentCart\Business\Model\QuoteItemOperationInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMerger;
use Spryker\Zed\PersistentCart\Business\Model\QuoteMergerInterface;
use Pyz\Zed\PersistentCart\Business\Model\QuoteResolver;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpander;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteStorageSynchronizerInterface;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriter;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeInterface;
use Spryker\Zed\PersistentCart\PersistentCartDependencyProvider;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartBusinessFactory extends SprykerPersistentCartBusinessFactory
{
    /**
     * @return \Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface
     */
    public function createQuoteResolver(): QuoteResolverInterface
    {
        return new QuoteResolver(
            $this->getQuoteFacade(),
            $this->createQuoteResponseExpander(),
            $this->getMessengerFacade(),
            $this->getStoreFacade(),
            $this->getConfig()
        );
    }
}
