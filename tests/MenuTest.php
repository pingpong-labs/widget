<?php

use Mockery as m;
use Pingpong\Menus\Menu;
use Pingpong\Menus\Builder;

class MenuTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function setUp()
	{
		$this->styles =  array(
			'navbar'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter',
			'navbar-right'	=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarRightPresenter',
			'nav-pills'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavPillsPresenter',
			'nav-tab'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavTabPresenter',
		);
	}

	public function testItInitialize()
	{
		$views = m::mock('Illuminate\View\Factory');
		$config = m::mock('Illuminate\Config\Repository');
		
		$menu = new Menu($views, $config);

		$this->assertInstanceOf('Pingpong\Menus\Menu', $menu);
	}

	public function testCreateMenuBuilderObject()
	{
		$config = m::mock('Illuminate\Config\Repository');
		$builder = new Builder('top', $config);
		$this->assertInstanceOf('Illuminate\Config\Repository', $config);
		$this->assertInstanceOf('Pingpong\Menus\Builder', $builder);
	}

	public function testCreateMenuFromBuilder()
	{
		$config = m::mock('Illuminate\Config\Repository');
		$builder = new Builder('top', $config);

		$home = $builder->add([
			'title'	=>	'Home',
			'url'	=>	'/',
			'icon'	=>	'fa fa-dashboard'
		]);

		$settings = $builder->add([
			'title'	=>	'Settings',
			'url'	=>	'/settings',
			'icon'	=>	'fa fa-tools'
		]);

		$this->assertInstanceOf('Pingpong\Menus\MenuItem', $home);
		$this->assertEquals($builder->count(), 2);
	}

	public function testCreateMenu()
	{
		$views = m::mock('Illuminate\View\Factory');
		$config = m::mock('Illuminate\Config\Repository');
		
		$menu = new Menu($views, $config);

		$topMenu = $menu->make('top-menu');
		$topMenu->add([
			'title'	=>	'Home',
			'url'	=>	'/',
			'icon'	=>	'fa fa-dashboard'
		]);
		$topMenu->add([
			'title'	=>	'Profile',
			'url'	=>	'/profile',
			'icon'	=>	'fa fa-profile'
		]);

		$this->assertEquals(1, $menu->count());
	}
}