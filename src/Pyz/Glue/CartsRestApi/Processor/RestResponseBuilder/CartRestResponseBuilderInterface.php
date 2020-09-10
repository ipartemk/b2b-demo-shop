<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface as SprykerCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CartRestResponseBuilderInterface extends SprykerCartRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartApprovalRestResponse(QuoteTransfer $quoteTransfer, string $localeName): RestResponseInterface;
}
