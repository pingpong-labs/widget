<?php

use Mockery as m;
use Pingpong\Menus\Menu;
use Pingpong\Menus\Builder;

class MenuTest extends PHPUnit_Framework_TestCase
{
	protected $styles;

	protected $views;

	protected $config;

	protected $menus;

	public function tearDown()
	{
		m::close();
	}

	public function setUp()
	{
		$this->views = m::mock('Illuminate\View\Factory');
		$this->config = m::mock('Illuminate\Config\Repository');
		
		$this->menu = new Menu($this->views, $this->config);
	}

	public function testItInitialize()
	{
		$this->assertInstanceOf('Pingpong\Menus\Menu', $this->menu);
	}

	public function testCreateMenuBuilderObject()
	{
		$builder = new Builder('top', $this->config);
		$this->assertInstanceOf('Illuminate\Config\Repository', $this->config);
		$this->assertInstanceOf('Pingpong\Menus\Builder', $builder);
	}

	public function testCreateMenuFromBuilder()
	{
		$builder = new Builder('top', $this->config);

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
		$topMenu = $this->menu->make('top-menu');
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

		$this->assertEquals(1, $this->menu->count());
	}
}