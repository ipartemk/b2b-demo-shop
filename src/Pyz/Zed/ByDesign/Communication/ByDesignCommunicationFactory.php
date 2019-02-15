<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Communication;

use Pyz\Zed\ByDesign\ByDesignDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 */
class ByDesignCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(ByDesignDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(ByDesignDependencyProvider::FACADE_OMS);
    }
}
