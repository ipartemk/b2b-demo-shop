<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi;

use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PersistentCart\Communication\Plugin\CartsRestApi\QuoteCreatorPlugin;
use Spryker\Zed\ProductOptionsRestApi\Communication\Plugin\CartsRestApi\ProductOptionCartItemMapperPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\CartsRestApi\SharedCartQuoteCollectionExpanderPlugin;
use Spryker\Zed\SharedCartsRestApi\Communication\Plugin\CartsRestApi\QuotePermissionGroupQuoteExpanderPlugin;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    public const FACADE_QUOTE_BASE = 'FACADE_QUOTE_BASE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addQuoteFacadeBase($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacadeBase(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE_BASE, function (Container $container) {
            return $container->getLocator()->quote()->facade();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        return new QuoteCreatorPlugin();
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionExpanderPluginInterface[]
     */
    protected function getQuoteCollectionExpanderPlugins(): array
    {
        return [
            new SharedCartQuoteCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    protected function getQuoteExpanderPlugins(): array
    {
        return [
            new QuotePermissionGroupQuoteExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\CartItemMapperPluginInterface[]
     */
    protected function getCartItemMapperPlugins(): array
    {
        return [
            new ProductOptionCartItemMapperPlugin(),
        ];
    }
}
