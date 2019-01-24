<?php

namespace Pyz\Zed\ByDesign\Business\Api\Converter;

use Generated\Shared\Transfer\ByDesignResponseTransfer;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Country\Business\CountryFacadeInterface;
use stdClass;

class ResponseConverter implements ResponseConverterInterface
{
    /**
     * @var \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @var \Pyz\Zed\ByDesign\Business\Api\Converter\ProcessStatusValidatorInterface
     */
    protected $processStatusValidator;

    /**
     * @param \Spryker\Zed\Country\Business\CountryFacadeInterface $countryFacade
     * @param \Pyz\Zed\ByDesign\Business\Api\Converter\ProcessStatusValidatorInterface $processStatusValidator
     */
    public function __construct(
//        CountryFacadeInterface $countryFacade,
//        ProcessStatusValidatorInterface $processStatusValidator
    ) {
//        $this->countryFacade = $countryFacade;
//        $this->processStatusValidator = $processStatusValidator;
    }

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
