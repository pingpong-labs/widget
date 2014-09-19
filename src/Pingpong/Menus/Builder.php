<?php namespace Pingpong\Menus;

use Illuminate\Config\Repository;

class Builder implements \Countable
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
	protected $styles = array();

	/**
	 * Constructor.
	 *
	 * @param  string  $menu
	 */
	public function __construct($menu, Repository $config)
	{
		$this->menu 	= $menu;
        $this->config   = $config;
	}

    /**
     * Set styles.
     * 
     * @param array $styles 
     */
    public function setStyles(array $styles)
    {
        $this->styles = $styles;
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
		if($this->hasStyle($name))
		{
			$this->setPresenter($this->getStyle($name));
		}
		return $this;
	}

    /**
     * Determine if the given name in the presenter style.
     *
     * @param $name
     * @return bool
     */
    public function hasStyle($name)
    {
        return array_key_exists($name, $this->getStyles());
    }

    /**
     * Get style aliases.
     * 
     * @return mixed 
     */
    public function getStyles()
    {
        return $this->styles ?: $this->config->get('menus::styles');
    }

    /**
     * Get the presenter class name by given alias name.
     *
     * @param $name
     * @return mixed
     */
    public function getStyle($name)
    {
        $style = $this->getStyles();

        return $style[$name];
    }

    /**
     * Set new presenter class from given alias name.
     *
     * @param $name
     */
    public function setPresenterFromStyle($name)
    {
        $this->setPresenter($this->getStyle($name));
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
     * Get items count.
     * 
     * @return int 
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Empty the current menu items.
     * 
     * @return void 
     */
    public function destroy()
    {
        $this->items = array();
    }

	/**
	 * Render the menu to HTML tag.
	 *
	 * @param  string  $presenter
	 * @return string
	 */
	public function render($presenter = null)
	{
        if($this->hasStyle($presenter))
        {
            $this->setPresenterFromStyle($presenter);
        }

		if( ! is_null($presenter) && ! $this->hasStyle($presenter))
		{
			$this->setPresenter($presenter);
		}

        return $this->renderMenu();
	}

    /**
     * Render the menu.
     *
     * @return string
     */
    protected function renderMenu()
    {
        $presenter  = $this->getPresenter();
        $menu       = $presenter->getOpenTagWrapper();

        foreach ($this->items as $item)
        {
            if ($item->hasSubMenu())
            {
                $menu .= $presenter->getMenuWithDropDownWrapper($item);
            }
            elseif($item->isHeader())
            {
                $menu .= $this->getHeaderWrapper($item);
            }
            elseif ($item->isDivider())
            {
                $menu .= $presenter->getDividerWrapper();
            }
            else
            {
                $menu .= $presenter->getMenuWithoutDropdownWrapper($item);
            }
        }
        $menu .= $presenter->getCloseTagWrapper();
        return $menu;
    }
}
