<?php

namespace Lar\LteAdmin\Core;

use Illuminate\Contracts\Support\Arrayable;
use Lar\LteAdmin\Core\Traits\FontAwesome;
use Lar\LteAdmin\Core\Traits\NavCommon;

/**
 * Class NavGroup
 * @package Lar\LteAdmin\Core
 */
class NavGroup implements Arrayable
{
    use FontAwesome, NavCommon;
    
    /**
     * @var array
     */
    protected $items = [];

    /**
     * NavItem constructor.
     * @param  string|null  $title
     * @param  string  $route
     */
    public function __construct(string $title = null, string $route = null)
    {
        $this->title($title)
            ->route($route);
    }

    /**
     * @param  \Closure  ...$closures
     * @return $this
     */
    public function do(...$closures)
    {
        foreach ($closures as $closure) {

            $closure($this);
        }

        return $this;
    }

    /**
     * @param  string|null  $title
     * @param  string|null  $route
     * @param  string|\Closure|null  $action
     * @return NavItem
     */
    public function item(string $title = null, string $route = null, $action = null)
    {
        $item = new NavItem($title, $route, $action);

        $this->items['items'][] = $item;

        return $item;
    }

    /**
     * @param  string|null  $title
     * @param  string|null|\Closure  $route
     * @param  \Closure|null  $cb
     * @return NavGroup
     */
    public function group(string $title = null, $route = null, \Closure $cb = null)
    {
        if ($route instanceof \Closure) {
            $cb = $route;
            $route = null;
        }

        $item = new NavGroup($title, $route);

        $this->items['items'][] = $item;

        if ($cb) {
            $cb($item);
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        if (isset($this->items['items'])) {
            foreach ($this->items['items'] as $key => $item) {
                /** @var Arrayable $item */
                $this->items['items'][$key] = $item->toArray();
            }
        }

        return $this->items;
    }
}