<?php

namespace Lar\LteAdmin\Segments\Tagable\Traits;

use Lar\LteAdmin\Segments\Tagable\Field;
use Lar\LteAdmin\Segments\Tagable\FormGroup;

/**
 * Trait FieldMassControl.
 * @package Lar\LteAdmin\Segments\Tagable\Traits
 */
trait FieldMassControl
{
    /**
     * @var bool
     */
    protected $vertical = false;

    /**
     * @var bool
     */
    protected $reversed = false;

    /**
     * @var bool
     */
    protected $set = true;

    /**
     * @var int|null
     */
    protected $label_width;

    /**
     * @return $this
     */
    public function vertical()
    {
        $this->vertical = true;

        return $this;
    }

    /**
     * @param $condition
     * @return $this
     */
    public function if($condition)
    {
        $this->set = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function reversed()
    {
        $this->reversed = true;

        return $this;
    }

    /**
     * @param  int  $width
     * @return $this
     */
    public function label_width(int $width)
    {
        $this->label_width = $width;

        return $this;
    }

    /**
     * @param $name
     * @param  array  $arguments
     * @return bool|FormGroup|mixed
     */
    protected function call_group($name, array $arguments)
    {
        if (isset(Field::$form_components[$name])) {
            $class = Field::$form_components[$name];

            $class = new $class(...$arguments);

            if ($class instanceof FormGroup) {
                $class->set_parent($this);

                if ($this->vertical) {
                    $class->vertical();
                }

                if ($this->reversed) {
                    $class->reversed();
                }

                if ($this->label_width !== null) {
                    $class->label_width($this->label_width);
                }
            }

            if ($this->set) {
                $this->appEnd($class);
            } else {
                $class->unregister();
            }

            $this->set = true;

            return $class;
        }

        return false;
    }

    /**
     * @param $name
     * @param  array  $arguments
     * @return bool|FormGroup|mixed
     */
    public static function static_call_group($name, array $arguments)
    {
        return (new Field())->{$name}(...$arguments);
    }
}
