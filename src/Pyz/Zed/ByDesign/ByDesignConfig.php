<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign;

use Pyz\Shared\ByDesign\ByDesignConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ByDesignConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getSoapLogin()
    {
        return $this->get(ByDesignConstants::SOAP_LOGIN);
    }

    /**
     * @return string
     */
    public function getSoapPassword()
    {
        return $this->get(ByDesignConstants::SOAP_PASSWORD);
    }
}
