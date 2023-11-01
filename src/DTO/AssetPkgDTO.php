<?php

namespace Kriss\ComposerAssetsPlugin\DTO;

class AssetPkgDTO extends BaseDTO
{
    /**
     * download url
     * @var string
     */
    public $url = '';
    /**
     * path to save
     * relative to extra['assets-dir']
     * @var string
     */
    public $save_path = '';
    /**
     * which composer require/require-dev dependent
     * skip install if dep not exist in require/require-dev
     * @var string
     */
    public $dep = '';
    /**
     * only need files after download
     * support fileNames and path
     * @var array
     */
    public $only_files = [];
}