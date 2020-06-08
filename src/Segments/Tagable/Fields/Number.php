<?php

namespace Lar\LteAdmin\Segments\Tagable\Fields;


/**
 * Class Email
 * @package Lar\LteAdmin\Segments\Tagable\Fields
 */
class Number extends Input
{
    /**
     * @var string
     */
    protected $type = "number";

    /**
     * @var string
     */
    protected $icon = null;

    /**
     * @var int
     */
    protected $value = 0;

    /**
     * @var string[]
     */
    protected $data = [
        'load' => 'number',
        'center' => 'false'
    ];

    /**
     * @param  int  $min
     * @return $this
     */
    public function min(int $min)
    {
        $this->params[]['min'] = $min;

        return $this;
    }

    /**
     * @param  int  $max
     * @return $this
     */
    public function max(int $max)
    {
        $this->params[]['max'] = $max;

        return $this;
    }

    /**
     * @return $this
     */
    public function center()
    {
        $this->data['center'] = 'true';

        return $this;
    }
}