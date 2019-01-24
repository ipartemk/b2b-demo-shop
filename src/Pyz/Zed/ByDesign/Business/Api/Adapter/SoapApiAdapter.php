<?php

namespace Pyz\Zed\ByDesign\Business\Api\Adapter;

use Exception;
use Generated\Shared\Transfer\ByDesignResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Zed\ByDesign\ByDesignConfig;
use Pyz\Zed\ByDesign\Business\Api\Converter\RequestConverterInterface;
use Pyz\Zed\ByDesign\Business\Api\Converter\ResponseConverterInterface;
use Pyz\Zed\ByDesign\Business\Api\Exception\ByDesignException;
use SoapClient;
use SoapFault;

class SoapApiAdapter implements ApiAdapterInterface
{
    public const WSDL_PATH = __DIR__ . "/../Etc/SalesOrderReplication.wsdl";
    public const PASSWORD = 'password';
    public const LOGIN = 'login';
    public const ADDRESSES = 'addresses';

    /**
     * @var \Pyz\Zed\ByDesign\Business\Api\Converter\RequestConverterInterface
     */
    protected $requestConverter;

    /**
     * @var \Pyz\Zed\ByDesign\Business\Api\Converter\ResponseConverterInterface
     */
    protected $responseConverter;

    /**
     * @var \Pyz\Zed\ByDesign\ByDesignConfig
     */
    protected $config;

    /**
     * @param \Pyz\Zed\ByDesign\Business\Api\Converter\RequestConverterInterface $requestConverter
     * @param \Pyz\Zed\ByDesign\Business\Api\Converter\ResponseConverterInterface $responseConverter
     * @param \Pyz\Zed\ByDesign\ByDesignConfig $config
     */
    public function __construct(
        RequestConverterInterface $requestConverter,
        ResponseConverterInterface $responseConverter,
        ByDesignConfig $config
    ) {

        $this->requestConverter = $requestConverter;
        $this->responseConverter = $responseConverter;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ByDesignResponseTransfer
     */
    public function create(OrderTransfer $orderTransfer)
    {
        $soapClient = $this->createSoapClient();
        $params = $this->buildRequestParameters($orderTransfer);
        $result = $soapClient->Create($params);

        try {
            $this->validateResponse($result);
        } catch (ByDesignException $exception) {
            return $this->createErrorResponseTransfer($exception);
        }

        return $this->responseConverter->convert($result);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function buildRequestParameters(OrderTransfer $orderTransfer)
    {
        return $this->requestConverter->convert($orderTransfer);
    }

    /**
     * @param \Exception $exception
     *
     * @return \Generated\Shared\Transfer\ByDesignResponseTransfer
     */
    protected function createErrorResponseTransfer(Exception $exception)
    {
        $responseTransfer = new ByDesignResponseTransfer();
        $responseTransfer->setIsError(true);
        $responseTransfer->setErrorMessage($exception->getMessage());

        return $responseTransfer;
    }

    /**
     * @return \SoapClient
     */
    protected function createSoapClient()
    {
        $options = $this->getRequestOptions();
        $soapClient = new SoapClient(static::WSDL_PATH, $options);

        return $soapClient;
    }

    /**
     * @return array
     */
    protected function getRequestOptions()
    {
        return [
            'trace' => true,
            'exceptions' => true,
            'login' => $this->config->getSoapLogin(),
            'password' => $this->config->getSoapPassword(),
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        ];
    }

    /**
     * @param \SoapFault|\stdClass $result
     *
     * @throws \Pyz\Zed\ByDesign\Business\Api\Exception\ByDesignException
     *
     * @return void
     */
    protected function validateResponse($result)
    {
        if (is_soap_fault($result)) {
            $message = $this->extractExceptionMessage($result);
            throw new ByDesignException($message);
        }
    }

    /**
     * @param \SoapFault $result
     *
     * @return string
     */
    protected function extractExceptionMessage(SoapFault $result)
    {
        if (isset($result->detail) && !empty(array_keys(get_object_vars($result->detail))[0])) {
            $exceptionName = array_keys(get_object_vars($result->detail))[0];

            return $result->detail->{$exceptionName}->Description;
        }

        return $result->getMessage();
    }
}
