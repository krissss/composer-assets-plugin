<?php

namespace Kriss\ComposerAssetsPlugin\DTO;

class BaseDTO
{
    public function __construct(array $attrs)
    {
        foreach ($attrs as $key => $value) {
            $key = str_replace('-', '_', $key);
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}