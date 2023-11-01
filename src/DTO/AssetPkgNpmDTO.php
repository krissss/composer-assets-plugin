<?php

namespace Kriss\ComposerAssetsPlugin\DTO;

class AssetPkgNpmDTO extends AssetPkgDTO
{
    /**
     * npm package name
     * @var string
     */
    public $name = '';
    /**
     * npm package version
     * @var string
     */
    public $version = '';

    public function __construct(array $attrs)
    {
        parent::__construct($attrs);

        if (!$this->url) {
            $this->url = "https://registry.npmjs.org/{$this->name}/-/{$this->name}-{$this->version}.tgz";
        }
        if (!$this->save_path) {
            $this->save_path = $this->name . '@' . $this->version;
        }
    }
}