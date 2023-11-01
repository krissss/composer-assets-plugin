<?php

namespace Kriss\ComposerAssetsPlugin\DTO;

class ExtraDTO extends BaseDTO
{
    /**
     * Asset save default path，for all pkgs
     * relative to vendor dir
     * @var string
     */
    public $assets_dir = '';
    /**
     * asset pkg
     * @var AssetPkgDTO[]
     */
    public $assets_pkgs = [];

    /**
     * type map
     * @var string[]
     */
    private $assetPkgTypeMap = [
        'url' => AssetPkgDTO::class,
        'npm' => AssetPkgNpmDTO::class,
        'github' => AssetPkgGithubDTO::class,
    ];

    public function __construct(array $attrs)
    {
        parent::__construct($attrs);

        $this->assets_pkgs = array_map(function (array $asset) {
            $assetClass = $this->assetPkgTypeMap[$asset['type'] ?? 'url'] ?? AssetPkgDTO::class;
            return new $assetClass($asset);
        }, $this->assets_pkgs);
    }
}
