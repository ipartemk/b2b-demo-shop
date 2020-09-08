<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote\Business;

use Pyz\Zed\Quote\Business\Model\QuoteWriter;
use Spryker\Zed\Quote\Business\Model\QuoteWriterInterface;
use Spryker\Zed\Quote\Business\QuoteBusinessFactory as SprykerQuoteBusinessFactory;

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
