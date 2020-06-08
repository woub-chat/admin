<?php

namespace Lar\LteAdmin\Segments\Tagable;

use Lar\Layout\Tags\DIV;
use Lar\LteAdmin\Core\Traits\FontAwesome;
use Lar\LteAdmin\Segments\Tagable\Traits\TypesTrait;

/**
 * Class Col
 * @package Lar\LteAdmin\Segments\Tagable
 */
class Alert extends DIV {

    use FontAwesome, TypesTrait;

    /**
     * @var string[]
     */
    protected $props = [
        'alert', 'role' => 'alert'
    ];

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $icon;

    /**
     * @var string|mixed
     */
    private $body;

    /**
     * @var array
     */
    private $params;

    /**
     * Alert constructor.
     * @param  string|null  $title
     * @param  string|null  $icon
     * @param $body
     * @param  mixed  ...$params
     */
    public function __construct(string $title = null, $body = '', string $icon = null, ...$params)
    {
        parent::__construct();

        $this->title = $title;

        $this->icon = $icon;

        $this->body = $body;

        $this->params = $params;

        $this->toExecute('_build');
    }

    /**
     * @param  array  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param  string  $icon
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param  array  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Build alert
     */
    protected function _build()
    {
        if ($this->title) {

            $h4 = $this->h4(['alert-heading']);

            if ($this->icon) {

                $h4->i([$this->icon]);
                $h4->text(':space');
            }

            if ($this->title) {

                $h4->text(__($this->title));
            }
        }

        if ($this->body) {

            $this->appEnd($this->body);
        }

        $this->when($this->params);

        $this->addClass("alert-{$this->type}");
    }
}