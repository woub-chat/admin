<?php

declare(strict_types=1);

namespace Admin\Components\SearchFields;

use Admin\Components\Inputs\ColorInput;

class ColorSearchInput extends ColorInput
{
    /**
     * @var string
     */
    public static string $condition = '=';
}
