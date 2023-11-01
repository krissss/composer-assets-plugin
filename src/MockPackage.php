<?php

namespace Kriss\ComposerAssetsPlugin;

use Composer\Package\Package;

class MockPackage extends Package
{
    public function __construct($url)
    {
        $urlName = strtr($url, [
            'https://' => '',
            'http://' => '',
            '/' => '-',
        ]);

        parent::__construct('composer-assets-plugin/' . $urlName, '0.0.0', '0.0.0');

        $this->setDistUrl($url);
        $this->setDistType($this->parseDistType($url));
        $this->setInstallationSource('dist');
    }

    protected function parseDistType($url): string
    {
        $parts = parse_url($url);
        $filename = pathinfo($parts['path'], PATHINFO_BASENAME);

        if (preg_match('/\.zip$/', $filename)) {
            return 'zip';
        }

        if (preg_match('/\.(tar\.gz|tgz)$/', $filename)) {
            return 'tar';
        }

        throw new \InvalidArgumentException("Failed to determine archive type for $filename");
    }
}