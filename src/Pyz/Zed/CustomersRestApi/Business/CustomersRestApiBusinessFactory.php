<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomersRestApi\Business;

use Pyz\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapper;
use Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory as SprykerCustomersRestApiBusinessFactory;
use Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapperInterface;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiBusinessFactory extends SprykerCustomersRestApiBusinessFactory
{
    /**
     * @return \Pyz\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapperInterface
     */
    public function createCustomerQuoteMapper(): CustomerQuoteMapperInterface
    {
        return new CustomerQuoteMapper($this->getCustomerFacade());
    }
}
