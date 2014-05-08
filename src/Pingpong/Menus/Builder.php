<?php namespace Pingpong\Menus;

class Builder
{
	/**
	 * @var string
	 */
	protected $menu;

	protected $items = array();

	protected $presenter = 'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter';

	public function __construct($menu)
	{
		$this->menu = $menu;
	}

	public function setPresenter($presenter)
	{
		$this->presenter = $presenter;
	}

	public function getPresenter()
	{
		return new $this->presenter;
	}

	public function add(array $attributes = array())
	{
		$newItem  		=  new MenuItem($attributes);
		$this->items[] 	= $newItem;
		return $newItem;
	}

	public function addDivider()
	{
		$this->items[] = new MenuItem(['name' => 'divider']);
		return $this;
	}

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
