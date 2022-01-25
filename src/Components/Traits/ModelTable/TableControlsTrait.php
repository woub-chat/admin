<?php

namespace Lar\LteAdmin\Components\Traits\ModelTable;

use Illuminate\Database\Eloquent\Model;
use Lar\Layout\Tags\SPAN;
use Lar\LteAdmin\Components\ButtonsComponent;
use Lar\LteAdmin\Core\PrepareExport;

trait TableControlsTrait
{
    /**
     * @var \Closure|array|null
     */
    protected $controls = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_info = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_edit = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_delete = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_force_delete = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_restore = null;

    /**
     * @var \Closure|array|null
     */
    protected $control_selectable = null;

    /**
     * @var bool
     */
    protected $checks = true;

    /**
     * @var bool
     */
    protected $check_delete = null;

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlGroup($test = null)
    {
        $this->set_test_var('controls', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlInfo($test = null)
    {
        $this->set_test_var('control_info', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlEdit($test = null)
    {
        $this->set_test_var('control_edit', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlDelete($test = null)
    {
        $this->set_test_var('control_delete', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlForceDelete($test = null)
    {
        $this->set_test_var('control_force_delete', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlRestore($test = null)
    {
        $this->set_test_var('control_restore', $test);

        return $this;
    }

    /**
     * @param  \Closure|array|mixed  $test
     * @return $this
     */
    public function controlSelect($test = null)
    {
        $this->set_test_var('control_selectable', $test);

        return $this;
    }

    /**
     * @param  null  $test
     * @return $this
     */
    public function checkDelete($test = null)
    {
        $this->set_test_var('check_delete', $test);

        return $this;
    }

    /**
     * @return $this
     */
    public function disableChecks()
    {
        $this->checks = false;

        return $this;
    }

    /**
     * @var array
     */
    protected $action = [];

    /**
     * @param $jax
     * @param $title
     * @param  null  $icon
     * @param  null  $confirm
     * @param  string|null  $warning
     * @return $this
     */
    public function action($jax, $title, $icon = null, $confirm = null, ?string $warning = 'lte.before_need_to_select')
    {
        $this->action[] = [
            'jax' => $jax,
            'title' => $title,
            'icon' => $icon,
            'confirm' => $confirm,
            'warning' => $warning,
        ];

        return $this;
    }

    public function getActionData()
    {
        $hasDelete = $this->get_test_var('check_delete') && gets()->lte->menu->now['link.destroy'](0);
        $select_type = request()->get($this->model_name.'_type', $this->order_type);
        $this->order_field = request()->get($this->model_name, $this->order_field);

        return [
            'table_id' => $this->model_name,
            'object' => $this->model_class,
            'hasHidden' => $this->hasHidden,
            'hasDelete' => $hasDelete,
            'show' => (count($this->action) || $hasDelete || count(PrepareExport::$columns) || $this->hasHidden) && $this->checks,
            'actions' => $this->action,
            'order_field' => $this->order_field,
            'select_type' => $select_type,
            'columns' => collect($this->columns)
                ->filter(static function ($i) {
                    return isset($i['field']) && is_string($i['field']) && ! $i['hide'];
                })
                ->pluck('field')
                ->toArray(),
            'all_columns' => collect($this->columns)
                ->filter(static function ($i) {
                    return isset($i['label']) && $i['label'];
                })
                ->map(static function ($i) {
                    unset($i['macros']);

                    return $i;
                })
                ->toArray(),
        ];
    }

    /**
     * Create default controls.
     */
    protected function _create_controls()
    {
        if ($this->get_test_var('controls')) {
            $hasDelete = gets()->lte->menu->now['link.destroy'](0);
            $show = count($this->action) || $hasDelete || count(PrepareExport::$columns) || $this->hasHidden;

            if ($this->checks && ! request()->has('show_deleted') && $show) {
                $this->to_prepend()->column(function (SPAN $span) use ($hasDelete) {
                    $span->_addClass('fit');
                    $span->view('lte::segment.model_table_checkbox', [
                        'id' => false,
                        'table_id' => $this->model_name,
                        'object' => $this->model_class,
                        'actions' => $this->action,
                        'delete' => $this->get_test_var('check_delete') && $hasDelete,
                        'columns' => collect($this->columns)->filter(static function ($i) {
                            return isset($i['field']) && is_string($i['field']);
                        })->pluck('field')->toArray(),
                    ])->render();
                }, function (Model $model) {
                    return view('lte::segment.model_table_checkbox', [
                        'id' => $model->id,
                        'table_id' => $this->model_name,
                        'disabled' => ! $this->get_test_var('control_selectable', [$model]),
                    ])->render();
                });
            }

            if (request()->has('show_deleted')) {
                $this->deleted_at();
            }

            $this->column(static function (SPAN $span) {
                $span->_addClass('fit');
            }, function (Model $model) {
                return ButtonsComponent::create()->when(function (ButtonsComponent $group) use ($model) {
                    $menu = gets()->lte->menu->now;

                    if ($menu) {
                        $key = $model->getRouteKey();

                        if (! request()->has('show_deleted')) {
                            if (isset($menu['link.edit']) && $this->get_test_var('control_edit', [$model]) && lte_controller_can('edit')) {
                                $group->resourceEdit($menu['link.edit']($key), '');
                            }

                            if (isset($menu['link.destroy']) && $this->get_test_var('control_delete', [$model]) && lte_controller_can('destroy')) {
                                $group->resourceDestroy($menu['link.destroy']($key), '', $model->getRouteKeyName(), $key);
                            }

                            if (isset($menu['link.show']) && $this->get_test_var('control_info', [$model]) && lte_controller_can('show')) {
                                $group->resourceInfo($menu['link.show']($key), '');
                            }
                        } else {
                            if (isset($menu['link.destroy']) && $this->get_test_var('control_restore', [$model]) && lte_controller_can('restore')) {
                                $group->resourceRestore($menu['link.destroy']($key), '', $model->getRouteKeyName(), $key);
                            }

                            if (isset($menu['link.destroy']) && $this->get_test_var('control_force_delete', [$model]) && lte_controller_can('force_destroy')) {
                                $group->resourceForceDestroy($menu['link.destroy']($key), '', $model->getRouteKeyName(), $key);
                            }
                        }
                    }
                });
            });
        }
    }

    /**
     * @param  string  $var_name
     * @param $test
     */
    protected function set_test_var(string $var_name, $test)
    {
        if (is_embedded_call($test)) {
            $this->{$var_name} = $test;
        } else {
            $this->{$var_name} = static function () use ($test) {
                return (bool) $test;
            };
        }
    }

    /**
     * @param  string  $var_name
     * @param  array  $args
     * @return bool
     */
    protected function get_test_var(string $var_name, array $args = [])
    {
        if ($this->{$var_name} !== null) {
            return call_user_func_array($this->{$var_name}, $args);
        }

        return true;
    }
}