<?php

namespace Admin\Components\Inputs;

use Admin\Traits\DateControlTrait;
use Illuminate\Support\Facades\App;

class DateTimeInput extends Input
{
    use DateControlTrait;

    /**
     * @var string|null
     */
    protected ?string $icon = 'fas fa-calendar-plus';

    /**
     * @var string[]
     */
    protected array $data = [
        'load' => 'picker::datetime',
        'toggle' => 'datetimepicker',
    ];

    /**
     * @var string
     */
    protected string $autocomplete = 'off';

    /**
     * @param  string  $name
     * @param  string|null  $title
     * @param ...$params
     */
    public function __construct(string $name, string $title = null, ...$params)
    {
        parent::__construct($name, $title, $params);

        $this->data['load-params'] = [App::getLocale()];
    }

    /**
     * On build.
     */
    protected function on_build(): void
    {
        $this->data['target'] = "#{$this->field_id}";
    }
}