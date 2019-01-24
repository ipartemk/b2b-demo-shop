<?php

namespace Pyz\Zed\ByDesign\Business\Api\Converter;

use Generated\Shared\Transfer\ByDesignResponseTransfer;
use stdClass;

class ResponseConverter implements ResponseConverterInterface
{
    /**
     * @param \stdClass $response
     *
     * @return \Generated\Shared\Transfer\ByDesignResponseTransfer
     */
    public function convert(stdClass $response)
    {
        $result = new ByDesignResponseTransfer();
        $result->setIsError(false);
        $result->setSalesOrderId($response->SalesOrder->ID->_);
        $result->setSalesOrderUuid($response->SalesOrder->UUID->_);

        return $result;
    }
}
