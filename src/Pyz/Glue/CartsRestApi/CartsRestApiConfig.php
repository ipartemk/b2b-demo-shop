<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig as SprykerCartsRestApiConfig;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class CartsRestApiConfig extends SprykerCartsRestApiConfig
{
    public const RESOURCE_CARTS_APPROVAL = 'carts-approval';
    public const CONTROLLER_CARTS_APPROVAL = 'carts-approval-resource';

}
