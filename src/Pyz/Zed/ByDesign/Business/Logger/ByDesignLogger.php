<?php

namespace Pyz\Zed\ByDesign\Business\Logger;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class ByDesignLogger implements ByDesignLoggerInterface
{
    use LoggerTrait;

    public const LOG_LINE_PREFIX = 'ByDesign: ';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    /**
     * @param string $message
     * @param mixed $context
     *
     * @return void
     */
    public function info($message, $context = null)
    {
        $this->logger->info(
            self::LOG_LINE_PREFIX . $message,
            $this->getContextArray($context)
        );
    }

    /**
     * @param mixed $context
     *
     * @return array
     */
    protected function getContextArray($context)
    {
        if ($context instanceof TransferInterface) {
            return $context->toArray();
        }

        if ($context === null) {
            return [];
        }

        return (array)$context;
    }
}
