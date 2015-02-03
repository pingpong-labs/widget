<?php

use Mockery as m;
use Pingpong\Menus\Builder;

class BuilderTest extends PHPUnit_Framework_TestCase {
	
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
		$this->config = m::mock('Illuminate\Config\Repository');
		$this->builder = new Builder('top-menu', $this->config);
		$this->builder->setStyles($this->styles);
	}

	public function testInitialize()
	{
		$this->assertInstanceOf('Pingpong\Menus\Builder', $this->builder);
	}

	public function testAliases()
	{
		$actual = $this->builder->hasStyle('navbar');
		$alias = $this->builder->getStyle('navbar');
		
		$this->assertTrue($actual);
		$this->assertEquals('Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter', $alias);
	}

	public function testGetAliasFromConfigFile()
	{
		$this->builder->setStyles(array());
		$this->config->shouldReceive('get')->times(2)->with('menus::styles')->andReturn(array('navbar' => $this->styles['navbar']));
		
		$actual = $this->builder->hasStyle('navbar');
		$alias = $this->builder->getStyle('navbar');

		$this->assertEquals('Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter', $alias);
		$this->assertTrue($actual);
	}
	
} 