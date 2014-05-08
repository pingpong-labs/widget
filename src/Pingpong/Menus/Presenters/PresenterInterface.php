<?php namespace Pingpong\Menus\Presenters;

interface PresenterInterface
{
	public function getOpenTagWrapper();

	public function getCloseTagWrapper();

	public function getMenuWithoutDropdownWrapper($item);

	public function getDividerWrapper();

	public function getMenuWithDropDownWrapper($item);

	public function getChildMenuItems($item);
}