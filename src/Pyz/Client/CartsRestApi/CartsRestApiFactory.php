<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CartsRestApi;

use Pyz\Client\CartsRestApi\Zed\CartsRestApiZedStub;
use Spryker\Client\CartsRestApi\CartsRestApiFactory as SprykerCartsRestApiFactory;
use Spryker\Client\CartsRestApi\Zed\CartsRestApiZedStubInterface;

class CartsRestApiFactory extends SprykerCartsRestApiFactory
{
    /**
     * @return \Pyz\Client\CartsRestApi\Zed\CartsRestApiZedStubInterface
     */
    public function createCartsRestApiZedStub(): CartsRestApiZedStubInterface
    {
        return new CartsRestApiZedStub($this->getZedRequestClient());
    }
}
