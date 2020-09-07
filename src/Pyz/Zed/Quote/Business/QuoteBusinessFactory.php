<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Quote\Business;

use Spryker\Zed\Quote\Business\QuoteBusinessFactory as SprykerQuoteBusinessFactory;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\GuestQuote\GuestQuoteDeleter;
use Spryker\Zed\Quote\Business\GuestQuote\GuestQuoteDeleterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteDeleter;
use Spryker\Zed\Quote\Business\Model\QuoteDeleterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteReader;
use Spryker\Zed\Quote\Business\Model\QuoteReaderInterface;
use Pyz\Zed\Quote\Business\Model\QuoteWriter;
use Spryker\Zed\Quote\Business\Model\QuoteWriterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutor;
use Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutorInterface;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfigurator;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfiguratorInterface;
use Spryker\Zed\Quote\Business\Quote\QuoteLocker;
use Spryker\Zed\Quote\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\Quote\Business\QuoteValidator\QuoteLockStatusValidator;
use Spryker\Zed\Quote\Business\QuoteValidator\QuoteLockStatusValidatorInterface;
use Spryker\Zed\Quote\Business\Validator\QuoteValidator;
use Spryker\Zed\Quote\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\Quote\QuoteConfig;
use Spryker\Zed\Quote\QuoteDependencyProvider;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 */
class QuoteBusinessFactory extends SprykerQuoteBusinessFactory
{
    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteWriterInterface
     */
    public function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createQuoteWriterPluginExecutor(),
            $this->getStoreFacade(),
            $this->createQuoteValidator(),
            $this->createQuoteFieldsConfigurator(),
            $this->getQuoteExpandBeforeCreatePlugins()
        );
    }
}
