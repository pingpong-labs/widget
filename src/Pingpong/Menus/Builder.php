<?php namespace Pingpong\Menus;

use Illuminate\Support\Facades\Config;

class Builder
{
	/**
	 * Menu name.
	 *
	 * @var string
	 */
	protected $menu;

	/**
	 * Array menu items.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Default presenter class.
	 *
	 * @var string
	 */
	protected $presenter = 'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter';

	/**
	 * Style name for each presenter.
	 *
	 * @var array
	 */
	protected $style;

	/**
	 * Constructor.
	 *
	 * @param  string  $menu
	 */
	public function __construct($menu)
	{
		$this->menu 	= $menu;
		$this->style 	= Config::get('menus::styles');
	}

	/**
	 * Set new presenter class.
	 *
	 * @param  string  $presenter
	 * @return void
	 */
	public function setPresenter($presenter)
	{
		$this->presenter = $presenter;
	}

	/**
	 * Get presenter instance.
	 *
	 * @return \Pingpong\Menus\Presenters\PresenterInterface
	 */
	public function getPresenter()
	{
		return new $this->presenter;
	}

	/**
	 * Set new presenter class by given style name.
	 *
	 * @param  string  $name
	 * @return self
	 */
	public function style($name)
	{
		if(array_key_exists($name, $this->style))
		{
			$this->setPresenter($this->style[$name]);
		}
		return $this;
	}

	/**
	 * Add new child menu.
	 *
	 * @param  array  $attributes
	 * @return \Pingpong\Menus\MenuItem
	 */
	public function add(array $attributes = array())
	{
        $item =  MenuItem::make($attributes);

        $this->items[] = $item;

        return $item;
    }

    /**
     * Create new menu with dropdown.
     *
     * @param $title
     * @param callable $callback
     * @return $this
     */
    public function dropdown($title, \Closure $callback)
    {
        $item = MenuItem::make(compact('title'));

        call_user_func($callback, $item);

        $this->items[] = $item;

        return $this;
    }

    /**
     * Register new menu item using registered route.
     *
     * @param $route
     * @param $title
     * @param array $parameters
     * @param array $attributes
     * @return static
     */
    public function route($route, $title, $parameters = array(), $attributes = array())
    {
        $item = MenuItem::make(array(
            'route'         =>  array($route, $parameters),
            'title'         =>  $title,
            'attributes'    =>  $attributes
        ));

        $this->items[] = $item;

        return $item;
    }

    /**
     * Register new menu item using url.
     *
     * @param $url
     * @param $title
     * @param array $attributes
     * @return static
     */
    public function url($url, $title, $attributes = array())
    {
        $item = MenuItem::make(array(
            'url'         =>  $url,
            'title'       =>  $title,
            'attributes'  =>  $attributes
        ));

        $this->items[] = $item;

        return $item;
    }

	/**
	 * Add new divider item.
	 *
	 * @return \Pingpong\Menus\MenuItem
	 */
	public function addDivider()
	{
		$this->items[] = new MenuItem(array('name' => 'divider'));
		return $this;
	}

	/**
	 * Render the menu to HTML tag.
	 *
	 * @param  string  $presenter
	 * @return string
	 */
	public function render($presenter = null)
	{
		if( ! is_null($presenter))
		{
			$this->setPresenter($presenter);
		}

		$menu = $this->getPresenter()->getOpenTagWrapper();
		foreach ($this->items as $item)
		{
			if($item->hasSubMenu())
			{
				$menu.= $this->getPresenter()->getMenuWithDropDownWrapper($item);
			}	
			elseif($item->isDivider())
			{
				$menu.= $this->getPresenter()->getDividerWrapper();
			}
			else
			{
				$menu.= $this->getPresenter()->getMenuWithoutDropdownWrapper($item);
			}
		}
		$menu.= $this->getPresenter()->getCloseTagWrapper();
		return $menu;
	}
}
