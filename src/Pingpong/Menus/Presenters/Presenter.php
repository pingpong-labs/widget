<?php namespace Pingpong\Menus\Presenters;

class Presenter implements PresenterInterface
{
	/**
	 * Get open tag wrapper.
	 *
	 * @return string
	 */
	public function getOpenTagWrapper() {}

	/**
	 * Get close tag wrapper.
	 *
	 * @return string
	 */
	public function getCloseTagWrapper() {}

	/**
	 * Get menu tag without dropdown wrapper.
	 *
	 * @param  \Pingpong\Menus\MenuItem  $item
	 * @return string
	 */
	public function getMenuWithoutDropdownWrapper($item) {}

	/**
	 * Get divider tag wrapper.
	 *
	 * @return string
	 */
	public function getDividerWrapper() {}

	/**
	 * Get menu tag with dropdown wrapper.
	 *
	 * @param  \Pingpong\Menus\MenuItem  $item
	 * @return string
	 */
	public function getMenuWithDropDownWrapper($item) {}

	/**
	 * Get child menu items.
	 *
	 * @param  \Pingpong\Menus\MenuItem  $item
	 * @return string
	 */
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