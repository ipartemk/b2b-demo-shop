<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ByDesign\Business\Logger;

interface ByDesignLoggerInterface
{
    /**
     * @param string $message
     * @param mixed $context
     *
     * @return void
     */
    public function info($message, $context = null);
}
