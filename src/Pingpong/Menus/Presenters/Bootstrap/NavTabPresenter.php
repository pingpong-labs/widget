<?php namespace Pingpong\Menus\Presenters\Bootstrap;

class NavTabPresenter extends NavbarPresenter
{
	public function getOpenTagWrapper()
	{
		return  PHP_EOL . '<ul class="nav nav-tabs">' . PHP_EOL;
	}
}