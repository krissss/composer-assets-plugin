<?php

namespace Kriss\ComposerAssetsPlugin;

class CommandProvider implements \Composer\Plugin\Capability\CommandProvider
{
    /**
     * @inheritDoc
     */
    public function getCommands()
    {
        return [
            new DownloadCommand(),
        ];
    }
}