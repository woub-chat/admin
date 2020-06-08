<?php

namespace Lar\LteAdmin\Segments\Tagable;

use Illuminate\Database\Eloquent\Model;
use Lar\Layout\Tags\BUTTON;
use Lar\Layout\Tags\DIV;

/**
 * Class ButtonGroup
 * @package Lar\LteAdmin\Segments\Tagable
 */
class ButtonGroup extends DIV {

    /**
     * @var array|null
     */
    protected $menu;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $action;

    /**
     * ButtonGroup constructor.
     * @param  array  $params
     */
    public function __construct(...$params)
    {
        parent::__construct();

        $this->addClass('btn-group btn-group-sm');

        $this->when($params);
        
        $this->menu = gets()->lte->menu->now;

        $this->model = gets()->lte->menu->model;

        $this->action = \Str::before(\Route::currentRouteAction(), '@');
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function info($ico, array $when = [])
    {
        return $this->btn('info', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function warning($ico, array $when = [])
    {
        return $this->btn('warning', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function danger($ico, array $when = [])
    {
        return $this->btn('danger', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function success($ico, array $when = [])
    {
        return $this->btn('success', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function secondary($ico, array $when = [])
    {
        return $this->btn('secondary', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function default($ico, array $when = [])
    {
        return $this->btn('default', $ico, $when);
    }

    /**
     * @param $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function primary($ico, array $when = [])
    {
        return $this->btn('primary', $ico, $when);
    }

    /**
     * @param  string  $type
     * @param  string|array  $ico
     * @param  array  $when
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function btn($type, $ico, array $when = [])
    {
        return $this->button(['btn btn-xs btn-' . $type])->when(function (BUTTON $button) use ($ico) {
            $button->setType('button');
            if (is_array($ico)) {
                $ico = array_values($ico);
                if (isset($ico[0]) && $ico[0]) { $button->i([$ico[0]]); }
                if (isset($ico[0]) && $ico[0] && isset($ico[1]) && $ico[1]) { $button->text(":space"); }
                if (isset($ico[1]) && $ico[1]) { $button->text("<span class='d-none d-sm-inline'>{$ico[1]}</span>"); }
            }
            else if ($ico) {

                $button->i([$ico]);
            }
        })->attr($when);
    }

    /**
     * Reload button
     * @param  string|null  $link
     * @param  string|null  $title
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function reload(string $link = null, string $title = null)
    {
        $return = $this->secondary(['fas fa-redo-alt', $title ?? __('lte.refresh')]);
        $return->dataClick()->location($link ?? \Request::getRequestUri());
        $return->setTitleIf($title === '', __('lte.refresh'));
        return $return;
    }

    /**
     * Nestable group
     */

    /**
     * @return $this
     */
    public function nestable()
    {
        $this->info(['far fa-minus-square', __('lte.collapse_all')])->setDatas(['click' => 'nestable::collapse']);
        $this->primary(['far fa-plus-square', __('lte.expand_all')])->setDatas(['click' => 'nestable::expand']);

        return $this;
    }
    
    /**
     * Resource group
     */


    /**
     * Resource list button
     * @param  string|null  $link
     * @param  string|null  $title
     * @return $this|\Lar\Layout\Abstracts\Component|BUTTON
     */
    public function resourceList(string $link = null, string $title = null)
    {
        if ($link || isset($this->menu['link'])) {

            $return = $this->primary(['fas fa-list-alt', $title ?? __('lte.list')]);
            $return->dataClick()->location($link ?? $this->menu['link']);
            $return->setTitleIf($title === '', __('lte.list'));
            return $return;
        }

        return $this;
    }

    /**
     * Resource edit button
     * @param  string|null  $link
     * @param  string|null  $title
     * @return $this|\Lar\Layout\Abstracts\Component|BUTTON
     */
    public function resourceEdit(string $link = null, string $title = null)
    {
        if (!$link && $this->model && $this->model->exists) {

            if (
                $key = $this->model->getRouteKey() &&
                isset($this->menu['link.edit'])
            ) {

                $link = $this->menu['link.edit']($key);
            }
        }

        if ($link) {

            $return = $this->success(['fas fa-edit', $title ?? __('lte.edit')]);
            $return->dataClick()->location($link);
            $return->setTitleIf($title === '', __('lte.edit'));
            return $return;
        }

        return $this;
    }

    /**
     * Resource info button
     * @param  string|null  $link
     * @param  string|null  $title
     * @return $this|\Lar\Layout\Abstracts\Component|BUTTON
     */
    public function resourceInfo(string $link = null, string $title = null)
    {
        if (!$link && $this->model && $this->model->exists) {

            if (
                $key = $this->model->getRouteKey() &&
                isset($this->menu['link.show'])
            ) {

                $link = $this->menu['link.show']($key);
            }
        }

        if ($link) {

            $return = $this->info(['fas fa-info-circle', $title ?? __('lte.information')]);
            $return->dataClick()->location($link);
            $return->setTitleIf($title === '', __('lte.information'));
            return $return;
        }

        return $this;
    }

    /**
     * Resource add button
     * @param  string|null  $link
     * @param  string|null  $title
     * @param  string|null  $rk_name
     * @param  null  $key
     * @return \Lar\Layout\Abstracts\Component|static|self||BUTTON
     */
    public function resourceDestroy(string $link = null, string $title = null, string $rk_name = null, $key = null)
    {
        if (!$link && $this->model && $this->model->exists) {

            if (
                $key = $this->model->getRouteKey() &&
                isset($this->menu['link.destroy'])
            ) {

                $link = $this->menu['link.destroy']($key);
            }
        }

        if ($link) {

            return $this->danger(['fas fa-trash-alt', $title ?? __('lte.delete')])->setDatas([
                'click' => 'alert::confirm',
                'params' => [
                    __('lte.delete_subject', ['subject' => strtoupper($rk_name ?? $this->model->getRouteKeyName()).":{$key}?"]),
                    $link . " >> \$jax.del"
                ]
            ])->setTitleIf($title === '', __('lte.delete'));
        }

        return $this;
    }

    /**
     * @param  array  $btn
     * @param  string|null  $form
     * @return \Lar\Layout\Abstracts\Component|BUTTON
     */
    public function submit($btn = null, string $form = null)
    {
        if (!$btn) {

            $btn = ['fas fa-save', __('lte.submit')];
        }

        $datas = [
            'click' => 'submit'
        ];

        if ($form) {

            $datas['form'] = $form;
        }

        return $this->success($btn)->setDatas($datas);
    }

    /**
     * Resource add button
     * @param  string  $link
     * @param  string  $title
     */
    public function resourceAdd(string $link = null, string $title = null)
    {
        if (!$link && isset($this->menu['link.create'])) {

            $link = $this->menu['link.create'];
        }

        if ($link) {
            $return = $this->success(['fas fa-plus', $title ?? __('lte.add')]);
            $return->setTitleIf($title === '', __('lte.add'));
            $return->dataClick()->location($link);
            return $return;
        }

        return $this;
    }
}