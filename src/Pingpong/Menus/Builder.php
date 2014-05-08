<?php namespace Pingpong\Menus;

class Builder
{
	/**
	 * @var string
	 */
	protected $menu;

	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @var string
	 */
	protected $presenter = 'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter';

	/**
	 * Constructor.
	 *
	 * @param  string  $menu
	 * @return void
	 */
	public function __construct($menu)
	{
		$this->menu = $menu;
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
	 * Add new child menu.
	 *
	 * @param  array  $attributes
	 * @return \Pingpong\Menus\MenuItem
	 */
	public function add(array $attributes = array())
	{
		$newItem  		=  new MenuItem($attributes);
		$this->items[] 	= $newItem;
		return $newItem;
	}

	/**
	 * Add new divider item.
	 *
	 * @return \Pingpong\Menus\MenuItem
	 */
	public function addDivider()
	{
		$this->items[] = new MenuItem(['name' => 'divider']);
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
