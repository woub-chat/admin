<?php

namespace Lar\LteAdmin\Segments\Tagable\SearchFields;

use Carbon\Carbon;

/**
 * Class DateTime.
 * @package Lar\LteAdmin\Segments\Tagable\Fields
 */
class DateTime extends \Lar\LteAdmin\Segments\Tagable\Fields\DateTime
{
    /**
     * @var string
     */
    public static $condition = '>=';

    /**
     * @param $value
     * @return Carbon
     */
    public static function transformValue($value)
    {
        return Carbon::create($value);
    }
}
