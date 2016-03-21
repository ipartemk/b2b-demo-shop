<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Pyz\Zed\Importer\Business\Installer;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface InstallerInterface
{

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(OutputInterface $output, MessengerInterface $messenger);

    /**
     * @return bool
     */
    public function isInstalled();

    /**
     * @return string
     */
    public function getTitle();

}