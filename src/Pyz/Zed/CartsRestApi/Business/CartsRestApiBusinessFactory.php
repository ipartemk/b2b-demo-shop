<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi\Business;

use Pyz\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory as SprykerCartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionCheckerInterface;

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
