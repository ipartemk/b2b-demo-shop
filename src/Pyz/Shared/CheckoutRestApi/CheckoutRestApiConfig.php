<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\CheckoutRestApi;

use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig as SprykerCheckoutRestApiConfig;

class CheckoutRestApiConfig extends SprykerCheckoutRestApiConfig
{
    public const ERROR_QUOTE_APPROVAL_NOT_FOUND = 'ERROR_QUOTE_APPROVAL_NOT_FOUND';
    public const ERROR_QUOTE_DECLINE_NOT_SUCCESSFUL = 'ERROR_QUOTE_DECLINE_NOT_SUCCESSFUL';
}
