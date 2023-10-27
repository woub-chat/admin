<?php

namespace Admin\Components\Inputs;

use Admin\Components\FormGroupComponent;
use Admin\Components\Inputs\Parts\InputSelect2;
use Admin\Core\Select2;
use Admin\Page;
use App;
use Illuminate\Contracts\Support\Arrayable;
use ReflectionException;

class SelectInput extends FormGroupComponent
{
    /**
     * @var string|null
     */
    protected ?string $icon = 'fas fa-mouse-pointer';

    /**
     * @var array
     */
    protected array $options = [];

    /**
     * @var string[]
     */
    protected array $data = [
        'load' => 'select2::init',
        'theme' => 'bootstrap4',
    ];

    /**
     * @var mixed
     */
    protected mixed $load_subject = null;

    /**
     * @var string|null
     */
    protected ?string $load_format = null;

    /**
     * @var mixed|callable
     */
    protected mixed $load_where;

    /**
     * @var bool
     */
    protected bool $nullable = false;

    /**
     * @var bool|string
     */
    protected string|bool $separator = ' ';

    /**
     * @var bool
     */
    protected bool $multiple = false;

    /**
     * @var mixed|null
     */
    public static mixed $json = null;

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public function field(): mixed
    {
        if ($this->load_subject) {
            $this->loadSubject();
        }

        $this->data['placeholder'] = $this->title;

        app(Page::class)->toStore('live', [$this->path => $this->value]);

        return InputSelect2::create($this->options)
            ->setAttributes($this->attributes)
            ->setName($this->name)
            ->setId($this->field_id)
            ->setValues($this->value)
            ->setMultiple($this->multiple)
            ->setHasBug($this->has_bug)
            ->makeOptions()
            ->setDatas($this->data)
            ->addClass($this->class);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    protected function loadSubject(): void
    {
        $selector = new Select2(
            $this->load_subject,
            $this->load_format,
            $this->value,
            $this->nullable ? $this->title : null,
            $this->field_id.'_',
            $this->load_where,
            $this->separator
        );

        $r_name = $selector->getName();

        if (request()->has($r_name)) {
            echo "\n" . $selector->toJson(JSON_UNESCAPED_UNICODE);
            http_response_code(200);
            die;
        }

        $this->data['select-name'] = $r_name;

        $this->data['load'] = 'select2::ajax';

        $vals = $selector->getValueData();

        $this->setSubjectValues($vals);
    }

    /**
     * @param $vals
     * @return void
     */
    protected function setSubjectValues($vals): void
    {
        if ($vals) {
            $this->options($vals, true);
        }
    }

    /**
     * @param  array|Arrayable  $options
     * @param  bool  $first_default
     * @return $this
     */
    public function options(array|Arrayable $options, bool $first_default = false): static
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $lang = App::getLocale();

        foreach ($options as $k => $option) {
            $this->options[$k] = $option;
        }

        foreach ($this->options as $k => $option) {
            if ($option && is_array($option)) {
                $this->options[$k] = $option[$lang] ?? implode(', ', $option);
            } else {
                $this->options[$k] = $option;
            }
        }

        if ($first_default && !$this->nullable) {
            $this->default(array_key_first($this->options));
        }

        return $this;
    }

    /**
     * @param  string  $separator
     * @return $this
     */
    public function separator(string $separator): static
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @param $subject
     * @param  string  $format
     * @param  callable|null  $where
     * @return $this
     */
    public function load($subject, string $format = 'id:name', callable $where = null): static
    {
        $this->load_subject = $subject;
        $this->load_format = $format;
        $this->load_where = $where;

        if ($where) {
            $this->data['with-where'] = 'true';
        }

        return $this;
    }

    /**
     * @param  string|null  $message
     * @return static
     */
    public function nullable(string $message = null): static
    {
        $this->nullable = true;

        if ($this->options) {
            $opts = ['' => ''];
            foreach ($this->options as $k => $option) {
                $opts[$k] = $option;
            }
            $this->options = $opts;
        } else {
            $this->options = ['' => ''];
        }

        $this->data['allow-clear'] = 'true';

        return parent::nullable($message);
    }
}