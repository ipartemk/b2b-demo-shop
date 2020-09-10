<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi;

use Pyz\Glue\CartsRestApi\Processor\Cart\CartApprovalReader;
use Pyz\Glue\CartsRestApi\Processor\Cart\CartApprovalReaderInterface;
use Pyz\Glue\CartsRestApi\Processor\Mapper\CartMapper;
use Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilder;
use Spryker\Glue\CartsRestApi\CartsRestApiFactory as SprykerCartsRestApiFactory;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @method \Pyz\Client\CartsRestApi\CartsRestApiClientInterface getClient()
 * @method \Pyz\Glue\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiFactory extends SprykerCartsRestApiFactory
{
    /**
     * @return \Pyz\Glue\CartsRestApi\Processor\Cart\CartApprovalReaderInterface
     */
    public function createCartApprovalReader(): CartApprovalReaderInterface
    {
        return new CartApprovalReader(
            $this->createCartRestResponseBuilder(),
            $this->getClient(),
            $this->getCustomerExpanderPlugins()
        );
    }

    /**
     * @return \Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    public function createCartRestResponseBuilder(): CartRestResponseBuilderInterface
    {
        return new CartRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCartMapper(),
            $this->createItemResponseBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface
     */
    public function createCartMapper(): CartMapperInterface
    {
        return new CartMapper(
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }
}
