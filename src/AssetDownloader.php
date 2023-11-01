<?php

namespace Kriss\ComposerAssetsPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Kriss\ComposerAssetsPlugin\DTO\ExtraDTO;

class AssetDownloader
{
    private $composer;
    private $io;
    private $filesystem;
    private $extra;

    public function __construct(Composer $composer, IOInterface $io, Filesystem $filesystem)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->filesystem = $filesystem;
        $this->extra = new ExtraDTO($this->composer->getPackage()->getExtra());
    }

    /**
     * entry
     * @return void
     */
    public function handle(): void
    {
        $assetSavePath = $this->getAssetSavePath();

        if (!isset($this->extra->assets_pkgs)) {
            throw new \InvalidArgumentException('config extra assets-pkgs first');
        }

        foreach ($this->extra->assets_pkgs as $asset) {
            if (!$asset->url || !$asset->save_path) {
                $this->io->warning('url and save_path must be set');
                continue;
            }
            if (!$this->checkDepExist($asset->dep)) {
                $this->io->warning("dep[{$asset->dep}] not exist, skip");
                continue;
            }

            $savePath = $assetSavePath . '/' . ltrim($asset->save_path, '/');
            $tempSavePath = $savePath . '_temp';

            $this->io->write("Install asset: {$asset->url} => {$savePath}");

            $this->download($asset->url, $tempSavePath);
            $this->keepOnlyFiles($tempSavePath, $savePath, $asset->only_files);
        }
    }

    /**
     * get Asset save path
     * @return string
     */
    private function getAssetSavePath(): string
    {
        $vendorDir = $this->composer->getConfig()->get('vendor-dir');

        return $this->filesystem->normalizePath($vendorDir . '/../' . $this->extra->assets_dir);
    }

    private $_deps = null;

    /**
     * check dep
     * @param string $dep
     * @return bool
     */
    private function checkDepExist(string $dep): bool
    {
        if (!$dep) {
            return true;
        }

        if ($this->_deps === null) {
            $package = $this->composer->getPackage();
            $this->_deps = array_merge(
                array_keys($package->getRequires()),
                array_keys($package->getDevRequires())
            );
        }

        return in_array($dep, $this->_deps);
    }

    /**
     * download from url to path
     * @param string $url
     * @param string $path
     * @return void
     * @throws \Throwable
     */
    private function download(string $url, string $path)
    {
        $package = new MockPackage($url);

        $downloadManager = $this->composer->getDownloadManager();
        $loop = $this->composer->getLoop();
        $loop->wait([$downloadManager->download($package, $path)]);
        $loop->wait([$downloadManager->install($package, $path)]);
    }

    /**
     * keep onlyFiles in targetPath from sourcePath
     * @param string $sourcePath
     * @param string $targetPath
     * @param array $onlyFiles
     * @return void
     */
    private function keepOnlyFiles(string $sourcePath, string $targetPath, array $onlyFiles = [])
    {
        if (!$onlyFiles) {
            $this->filesystem->rename($sourcePath, $targetPath);
            return;
        }

        foreach ($onlyFiles as $file) {
            $fromFile = $sourcePath . '/' . $file;
            $toFile = $targetPath . '/' . $file;
            $this->filesystem->ensureDirectoryExists(is_dir($toFile) ? $toFile : dirname($toFile));
            if (!file_exists($fromFile)) {
                $this->io->warning("file not exist: {$targetPath}");
                continue;
            }
            $this->filesystem->copy($fromFile, $toFile);
        }
        $this->filesystem->removeDirectory($sourcePath);
    }
}