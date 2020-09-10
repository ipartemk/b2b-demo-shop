<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\CartsRestApi;

use Spryker\Client\CartsRestApi\CartsRestApiFactory as SprykerCartsRestApiFactory;
use Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Pyz\Client\CartsRestApi\Zed\CartsRestApiZedStub;
use Spryker\Client\CartsRestApi\Zed\CartsRestApiZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

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
