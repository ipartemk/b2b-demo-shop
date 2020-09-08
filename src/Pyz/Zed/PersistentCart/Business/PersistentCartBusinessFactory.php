<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PersistentCart\Business;

use Pyz\Zed\PersistentCart\Business\Model\QuoteResolver;
use Spryker\Zed\PersistentCart\Business\Model\QuoteResolverInterface;
use Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory as SprykerPersistentCartBusinessFactory;

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
