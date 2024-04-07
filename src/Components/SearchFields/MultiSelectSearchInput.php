<?php

declare(strict_types=1);

namespace Admin\Components\SearchFields;

use Admin\Components\Inputs\MultiSelectInput;

class MultiSelectSearchInput extends MultiSelectInput
{
    /**
     * @var string
     */
    public static string $condition = 'in';
}
