<?php

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
