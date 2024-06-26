<?php

declare(strict_types=1);

namespace Admin\Core;

use Admin\ExtendProvider;
use Admin\Interfaces\NavigateInterface;
use Admin\Navigate;
use Closure;

/**
 * @mixin NavigatorMethods
 */
class NavigatorExtensionProvider implements NavigateInterface
{
    /**
     * @var NavigateInterface|Navigate|NavGroup
     */
    public $navigate;

    /**
     * @var ExtendProvider
     */
    public $provider;

    /**
     * NavigatorExtensionProvider constructor.
     * @param  NavigateInterface  $navigate
     * @param  ExtendProvider  $provider
     */
    public function __construct(NavigateInterface $navigate, ExtendProvider $provider)
    {
        $this->navigate = $navigate;
        $this->provider = $provider;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
    }

    /**
     * @param  string  $title
     * @return Navigate
     */
    public function menu_header(string $title)
    {
        return $this->navigate->menu_header($title);
    }

    /**
     * @param  string|null  $title
     * @param  null  $route
     * @param  Closure|array|null  $cb
     * @return NavGroup
     */
    public function group(string $title = null, $route = null, $cb = null)
    {
        return $this->navigate->group($title, $route, $cb);
    }

    /**
     * @param  string|null  $title
     * @param  string|null  $route
     * @param  null  $action
     * @return NavItem
     */
    public function item(string $title = null, string $route = null, $action = null)
    {
        return $this->navigate->item($title, $route, $action);
    }

    /**
     * @param  string  $view
     * @param  array  $params
     * @param  bool  $prepend
     * @return NavGroup|Navigate
     */
    public function nav_bar_view(string $view, array $params = [], bool $prepend = false)
    {
        return $this->navigate->nav_bar_view($view, $params, $prepend);
    }

    /**
     * @param  string  $view
     * @param  array  $params
     * @param  bool  $prepend
     * @return NavGroup|Navigate
     */
    public function nav_bar_vue(string $view, array $params = [], bool $prepend = false)
    {
        $return = $this->navigate->nav_bar_vue($view, $params, $prepend);

        return $return;
    }

    /**
     * @param  string  $view
     * @param  array  $params
     * @return NavGroup|Navigate
     */
    public function left_nav_bar_view(string $view, array $params = [])
    {
        return $this->navigate->left_nav_bar_view($view, $params);
    }

    /**
     * @param  string  $view
     * @param  array  $params
     * @return NavGroup|Navigate
     */
    public function left_nav_bar_vue(string $view, array $params = [])
    {
        return $this->navigate->left_nav_bar_vue($view, $params);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->navigate->{$name}(...$arguments);
    }
}
