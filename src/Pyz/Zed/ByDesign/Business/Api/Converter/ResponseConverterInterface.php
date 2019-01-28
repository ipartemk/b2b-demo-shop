<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
