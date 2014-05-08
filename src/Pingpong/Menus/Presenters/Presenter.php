<?php namespace Pingpong\Menus\Presenters;

class Presenter implements PresenterInterface
{
	public function getOpenTagWrapper() {}

	public function getCloseTagWrapper() {}

	public function getMenuWithoutDropdownWrapper($item) {}

	public function getDividerWrapper() {}

	public function getMenuWithDropDownWrapper($item) {}

	public function getChildMenuItems($item)
	{
		$results = '';
		foreach ($item->getChilds() as $child)
		{
			if($child->isDivider())
			{
				$results.= $this->getDividerWrapper();
			}
			else
			{
				$results.= $this->getMenuWithoutDropdownWrapper($child);
			}
		}
		return $results;
	}
}