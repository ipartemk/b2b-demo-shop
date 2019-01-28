<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Business;

use Pyz\Zed\ByDesign\Business\Api\Adapter\SoapApiAdapter;
use Pyz\Zed\ByDesign\Business\Api\Converter\RequestConverter;
use Pyz\Zed\ByDesign\Business\Api\Converter\ResponseConverter;
use Pyz\Zed\ByDesign\Business\Logger\ByDesignLogger;
use Pyz\Zed\ByDesign\Business\Model\OrderCreator;
use Pyz\Zed\ByDesign\ByDesignDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * @method \Pyz\Zed\ByDesign\ByDesignConfig getConfig()
 */
class ByDesignBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ByDesign\Business\Model\OrderCreatorInterface
     */
    public function createOrderCreator()
    {
        return new OrderCreator(
            $this->createSoapApiAdapter(),
            $this->getConfig(),
            $this->createAddressDoctorLogger()
        );
    }

    /**
     * @return \Pyz\Zed\ByDesign\Business\Api\Adapter\ApiAdapterInterface
     */
    protected function createSoapApiAdapter()
    {
        return new SoapApiAdapter(
            $this->createRequestConverter(),
            $this->createResponseConverter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\ByDesign\Business\Api\Converter\RequestConverterInterface
     */
    protected function createRequestConverter()
    {
        return new RequestConverter();
    }

    /**
     * @return \Pyz\Zed\ByDesign\Business\Api\Converter\ResponseConverterInterface
     */
    protected function createResponseConverter()
    {
        return new ResponseConverter();
    }

    /**
     * @return \Pyz\Zed\ByDesign\Business\Logger\ByDesignLoggerInterface
     */
    protected function createAddressDoctorLogger()
    {
        return new ByDesignLogger();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(ByDesignDependencyProvider::FACADE_SALES);
    }
}
