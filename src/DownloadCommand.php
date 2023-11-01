<?php

namespace Kriss\ComposerAssetsPlugin;

use Composer\Command\BaseCommand;
use Composer\Util\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('assets-download');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new AssetDownloader($this->requireComposer(), $this->getIO(), new Filesystem()))->handle();

        return self::SUCCESS;
    }
}