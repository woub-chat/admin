<?php

namespace Lar\LteAdmin\Segments\Tagable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ViewErrorBag;
use Lar\Layout\Abstracts\Component;
use Lar\Layout\Tags\DIV;
use Lar\Layout\Tags\I;
use Lar\LteAdmin\Core\Traits\FontAwesome;
use Lar\LteAdmin\Segments\Tagable\Traits\FormGroupRulesTrait;

/**
 * Class Col
 * @package Lar\LteAdmin\Segments\Tagable
 */
abstract class FormGroup extends DIV {

    use FormGroupRulesTrait, FontAwesome;

    /**
     * @var bool
     */
    protected $only_content = true;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $icon = "fas fa-pencil-alt";

    /**
     * @var string
     */
    protected $info;

    /**
     * @var int
     */
    protected $label_width = 2;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $vertical = false;

    /**
     * @var bool
     */
    protected $reversed = false;

    /**
     * @var Component|Form
     */
    protected $parent_field;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $field_id;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $has_bug = false;

    /**
     * @var ViewErrorBag
     */
    protected $errors;

    /**
     * FormGroup constructor.
     * @param  Component  $parent
     * @param  string  $title
     * @param  string  $name
     * @param  mixed  ...$params
     */
    public function __construct(Component $parent, string $name, string $title = null, ...$params)
    {
        parent::__construct();

        $this->title = $title ? __($title) : $title;
        $this->name = $name;
        $this->params = array_merge($this->params, $params);
        $this->parent_field = $parent;
        $this->field_id = 'input_' . \Str::slug($this->name, '_');
        $this->path = trim(str_replace(['[',']'], '.', str_replace('[]', '', $name)), '.');
        $this->errors = request()->session()->get('errors') ?: new ViewErrorBag;
        $this->has_bug = $this->errors->getBag('default')->has($name);

        $this->toExecute('makeWrapper');
    }

    /**
     * @return void
     */
    protected function on_build() {}

    /**
     * Make wrapper for input
     */
    protected function makeWrapper()
    {
        $this->on_build();

        $fg = $this->div(['form-group row']);

        if (!$this->reversed) {

            $this->make_label($fg);
        }

        $icon = is_string($this->icon) && preg_match('/^(fas\s|fab\s|far\s)fa\-[a-zA-Z0-9\-\_]+/', $this->icon) ?
            I::create([$this->icon]) : $this->icon;

        $group_width = 12 - $this->label_width;
        $input_group = $fg->div()->addClassIf($icon, 'input-group')
            ->addClassIf($this->vertical, 'w-100')
            ->addClassIf(!$this->vertical && $this->title, "col-sm-{$group_width}");

        $this->make_icon_wrapper($input_group, $icon);

        $fg->setDatas(['label-width' => $this->label_width]);

        if ($this->vertical) {

            $fg->setDatas(['vertical' => 'true']);
        }

        $this->value = $this->create_value();

        $input_group->appEnd(
            $this->field()
        )->appEnd(
            $this->app_end_field()
        );

        if ($this->reversed) {

            $this->make_label($fg);
        }

        $this->make_info_message($fg)
            ->make_error_massages($fg);
    }

    /**
     * @return mixed
     */
    protected function create_value () {

        return $this->model ? (multi_dot_call($this->model, $this->path) ?? $this->value): $this->value;
    }

    /**
     * @return string
     */
    protected function app_end_field()
    {
        return '';
    }

    /**
     * @param  DIV  $form_group
     */
    protected function make_label(DIV $form_group)
    {
        if ($this->title) {

            $form_group->label(['for' => $this->field_id, 'class' => 'col-form-label'], $this->title)
                ->addClassIf(!$this->vertical, 'col-sm-'.$this->label_width);
        }
    }

    /**
     * @param  DIV  $input_group
     * @param mixed $icon
     * @return $this
     */
    protected function make_icon_wrapper(DIV $input_group, $icon = null)
    {
        if ($icon) {

            $input_group->div(['class' => 'input-group-prepend'])
                ->span(['class' => 'input-group-text'], $icon);
        }

        return $this;
    }

    /**
     * @param  DIV  $fg
     * @return $this
     */
    protected function make_info_message(DIV $fg)
    {
        if ($this->info) {

            $group_width = 12 - $this->label_width;

            if (!$this->vertical) {
                $fg->div(["col-sm-{$this->label_width}"]);
            }
            $fg->small(['text-primary invalid-feedback d-block'])
                ->addClassIf(!$this->vertical, "col-sm-{$group_width}")
                ->i(['fas fa-info-circle'])->_text(":space", $this->info);
        }

        return $this;
    }

    /**
     * @param  DIV  $fg
     * @return $this
     */
    protected function make_error_massages(DIV $fg)
    {
        if ($this->name && $this->errors && $this->errors->has($this->name)) {

            $group_width = 12 - $this->label_width;

            $messages = $this->errors->get($this->name);

            foreach ($messages as $mess) {

                if (!$this->vertical) {
                    $fg->div(["col-sm-{$this->label_width}"]);
                }
                $fg->small(['error invalid-feedback d-block'])
                    ->addClassIf(!$this->vertical, "col-sm-{$group_width}")
                    ->small(['fas fa-exclamation-triangle'])->_text(":space", $mess);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function vertical()
    {
        $this->vertical = true;

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
     * @param  string  $icon
     * @return $this
     */
    public function icon(string $icon)
    {
        if ($this->icon !== null) {

            $this->icon = $icon;
        }

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
     * @param  array  $datas
     * @return $this
     */
    public function mergeDataList(array $datas)
    {
        $this->data = array_merge($this->data, $datas);

        return $this;
    }

    /**
     * @param  array  $rules
     * @return $this
     */
    public function mergeRuleList(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function default($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param  Model  $model
     * @return $this
     */
    public function setModel(Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param  string  $info
     * @return $this
     */
    public function info(string $info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return \Lar\Layout\Tags\INPUT|mixed
     */
    abstract public function field();
}