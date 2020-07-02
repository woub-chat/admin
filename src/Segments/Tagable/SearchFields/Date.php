<?php

namespace Lar\LteAdmin\Segments\Tagable\SearchFields;

use Carbon\Carbon;

/**
 * Class Date
 * @package Lar\LteAdmin\Segments\Tagable\Fields
 */
class Date extends \Lar\LteAdmin\Segments\Tagable\Fields\Date
{
    /**
     * @var string
     */
    static $condition = ">=";

    /**
     * @param $value
     * @return Carbon
     */
    static function transformValue ($value) {

        return Carbon::create($value);
    }
}