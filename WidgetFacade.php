<?php namespace Pingpong\Widget;

use Illuminate\Support\Facades\Facade;

class WidgetFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'widget'; }

}