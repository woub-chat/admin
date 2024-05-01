<?php

declare(strict_types=1);

namespace Admin\Delegates;

use Admin\Components\ModelCardsComponent;
use Admin\Core\Delegator;
use Closure;
use Illuminate\Support\Traits\Macroable;

/**
 * @mixin ModelCardsComponent
 * @mixin MacroMethodsForModelCards
 */
class ModelCards extends Delegator
{
    use Macroable;

    protected $class = ModelCardsComponent::class;

    public function rowDefault(...$delegates): array
    {
        return [
            $this->id(),
            ...$delegates,
            $this->at(),
        ];
    }

    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            $macro = static::$macros[$method];

            if ($macro instanceof Closure) {
                $macro = $macro->bindTo($this, static::class);
            }

            return $macro(...$parameters);
        }

        return parent::__call($method, $parameters);
    }
}