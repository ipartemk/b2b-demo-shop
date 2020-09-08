<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi\Processor\Approve;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer;

interface QuoteDeclineMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteDeclineResponseAttributesTransfer
     */
    public function mapQuoteTransferToRestQuoteDeclineResponseAttributesTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteDeclineRequestAttributesTransfer $restQuoteDeclineAttributesTransfer
    ): RestQuoteDeclineResponseAttributesTransfer;
}
