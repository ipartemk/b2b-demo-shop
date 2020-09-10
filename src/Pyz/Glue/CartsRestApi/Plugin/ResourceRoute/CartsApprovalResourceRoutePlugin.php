<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi\Plugin\ResourceRoute;

use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Pyz\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Pyz\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsApprovalResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection
            ->addGet('get');

        return $resourceRouteCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return CartsRestApiConfig::RESOURCE_CARTS_APPROVAL;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return CartsRestApiConfig::CONTROLLER_CARTS_APPROVAL;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestCartsAttributesTransfer::class;
    }
}
