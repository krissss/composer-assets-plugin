<?php

namespace Kriss\ComposerAssetsPlugin\DTO;

class AssetPkgGithubDTO extends AssetPkgDTO
{
    /**
     * github repo name
     * @var string
     */
    public $name = '';
    /**
     * github tag version
     * @var string
     */
    public $version = '';

    public function __construct(array $attrs)
    {
        parent::__construct($attrs);

        if (!$this->url) {
            $this->url = "https://github.com/{$this->name}/archive/refs/tags/{$this->version}.tar.gz";
        }
        if (!$this->save_path) {
            $this->save_path = $this->name . '@' . $this->version;
        }
    }
}