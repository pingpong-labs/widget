<?php

use Mockery as m;
use Pingpong\Widget\Widget;
use Illuminate\Container\Container;
use Illuminate\Html\HtmlBuilder as HTML;

class WidgetTest extends PHPUnit_Framework_TestCase {
	
	protected $blade;

	protected $container;

	protected $widget;

	public function tearDown()
	{
		m::close();
	}

	public function setUp()
	{
		$this->blade = m::mock('Illuminate\View\Compilers\BladeCompiler');
		$this->container = new Container;
		$this->widget = new Widget($this->blade, $this->container);	
	}
	
	public function testInitialize()
	{
		$this->assertInstanceOf('Pingpong\Widget\Widget', $this->widget);
	}

	public function testRegisterWidgetViaClosure()
	{
		$this->blade->shouldReceive('extend')->once();

		$this->widget->register('hello', function()
		{
			return 'Hello World!';	
		});

		$this->assertEquals('Hello World!', $this->widget->call('hello'));
	}

	public function testRegisterWidgetViaFunctionName()
	{
		$this->blade->shouldReceive('extend')->once();

		$this->widget->register('welcome', 'showWelcome');

		$this->assertEquals('Welcome! John!', $this->widget->welcome('John!'));
	}

	public function testRegisterWidgetViaClasses()
	{		
		$this->blade->shouldReceive('extend')->times(2);

		$this->widget->register('h1', 'HTMLWidget');
		$this->widget->register('p', 'HTMLWidget@p');

		$h1 = $this->widget->h1('Hello there!');
		$p = $this->widget->p('Hello there!', array('class' => 'well'));

		$this->assertEquals('<h1>Hello there!</h1>', $h1);

		$this->assertEquals('<p class="well">Hello there!</p>', $p);
	}

	public function testGroupingAWidget()
	{
		$this->blade->shouldReceive('extend')->times(3);

		$this->widget->register('h1', 'HTMLWidget');
		$this->widget->register('p', 'HTMLWidget@p');

		$this->widget->group('html', array('h1', 'p'));

		$actual = $this->widget->html(array('Hello'), array('You are ok!'));
		$expected = '<h1>Hello</h1><p>You are ok!</p>';

		$this->assertEquals($expected, $actual);
	}

	public function testSubscribeWidget()
	{
		$this->blade->shouldReceive('extend')->times(3);
		$this->widget->subscribe('WidgetSubscriber');

		$this->assertEquals('foo', $this->widget->foo());
		$this->assertEquals('bar', $this->widget->bar());
		$this->assertEquals('baz', $this->widget->baz());
	}

} 

class WidgetSubscriber {

	public function subscribe($widget)
	{
		$widget->register('foo', __CLASS__ .'@foo');
		$widget->register('bar', __CLASS__ .'@bar');
		$widget->register('baz', __CLASS__ .'@baz');
	}

	public function __call($method, $args)
	{
		return $method;
	}
	
}

class TagCreator {
	
	protected $html;

	public function __construct(HTML $html)
	{
		$this->html = $html;
	}	

	public function create($tag, $contents, $attributes = array())
	{
		$attributes = $this->html->attributes($attributes);

		return "<{$tag}{$attributes}>{$contents}</{$tag}>";
	}

} 

class HTMLWidget {

	protected $tag;

	public function __construct(TagCreator $tag)
	{
		$this->tag = $tag;
	}
	
	public function register($contents, $attributes = array())
	{
		return $this->tag->create('h1', $contents, $attributes);
	}

	public function p($contents, $attributes = array())
	{
		return $this->tag->create('p', $contents, $attributes);
	}
} 


function showWelcome($name)
{
	return 'Welcome! ' . $name;
}