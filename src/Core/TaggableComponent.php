<?php

namespace LteAdmin\Core;

use Lar\Layout\Abstracts\Component;
use LteAdmin\Components\AlertComponent;
use LteAdmin\Components\DividerComponent;
use LteAdmin\Components\InfoBoxComponent;
use LteAdmin\Components\LangComponent;
use LteAdmin\Components\SmallBoxComponent;
use LteAdmin\Components\StatisticPeriodComponent;
use LteAdmin\Components\TableComponent;
use LteAdmin\Components\TabsComponent;
use LteAdmin\Components\TimelineComponent;
use LteAdmin\Controllers\Controller;

class TaggableComponent extends Component
{
    /**
     * @var string[]
     */
    protected static $collection = [

    ];

    /**
     * TagableComponent constructor.
     */
    public function __construct()
    {
        static::injectCollection(static::$collection);
        static::injectCollection(Controller::getExplanationList());
    }
}
