<?php

declare(strict_types=1);

namespace Admin\Delegates;

use Admin\Components\ModelRelationContentComponent;
use Admin\Core\Delegator;
use Illuminate\Support\Traits\Macroable;

/**
 * @mixin ModelRelationContentComponent
 * @mixin MacroMethodsForModelRelation
 */
class ModelRelation extends Delegator
{
    use Macroable;

    protected $class = ModelRelationContentComponent::class;

    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {

            $macro = static::$macros[$method];

            if ($macro instanceof \Closure) {
                $macro = $macro->bindTo($this, static::class);
            }

            return $macro(...$parameters);
        }

        return parent::__call($method, $parameters);
    }
}
