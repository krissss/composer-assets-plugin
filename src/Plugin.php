<?php

namespace Kriss\ComposerAssetsPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;

class Plugin implements PluginInterface, Capable, EventSubscriberInterface
{
    protected $composer;
    protected $io;

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => CommandProvider::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'triggerDownload',
            ScriptEvents::POST_UPDATE_CMD => 'triggerDownload',
        ];
    }

    public function triggerDownload()
    {
        (new AssetDownloader($this->composer, $this->io, new Filesystem()))
            ->handle();
    }
}