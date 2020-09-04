<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CustomersRestApi\Business;

use Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory as SprykerCustomersRestApiBusinessFactory;
use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriter;
use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriterInterface;
use Spryker\Zed\CustomersRestApi\Business\Addresses\Mapper\AddressQuoteMapper;
use Spryker\Zed\CustomersRestApi\Business\Addresses\Mapper\AddressQuoteMapperInterface;
use Pyz\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapper;
use Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapperInterface;
use Spryker\Zed\CustomersRestApi\CustomersRestApiDependencyProvider;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
