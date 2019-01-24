<?php

namespace Pyz\Zed\ByDesign\Business\Api\Converter;

use stdClass;

interface ResponseConverterInterface
{
    /**
     * @param \stdClass $response
     *
     * @return \Generated\Shared\Transfer\ByDesignResponseTransfer
     */
    public function convert(stdClass $response);
}
