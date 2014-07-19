<?php namespace Pingpong\Widget;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;

class Widget {
	
	protected $blade;

	protected $container;

	protected $groups = array();

	protected $widgets = array();

	public function __construct(BladeCompiler $blade, Container $container)
	{
		$this->blade = $blade;
		$this->container = $container;
	}	

	public function register($name, $callback)
	{
		$this->widgets[$name] = $callback;

		$this->registerBlade($name);
	}

	protected function registerBlade($name)
	{
		$this->blade->extend(function($view) use ($name)
		{
			return $this->createReplacement($name, $view);
		});
	}

	protected function createReplacement($name, $view)
	{
		return preg_replace("/@$name\((.*)\)/",	"<?php echo Widget::$name($1); ?>", $view);
	}

	public function has($name)
	{
		return array_key_exists($name, $this->widgets);
	}

	public function call($name, array $parameters = array())
	{
		return $this->get($name, $parameters);
	}

	public function get($name, array $parameters = array())
	{
		if($this->hasGroup($name)) return $this->callGroup($name, $parameters);

		if($this->has($name))
		{
			$callback = $this->widgets[$name];
			
			return $this->getCallback($callback, $parameters);
		}
		return null;
	}

	protected function getCallback($callback, $parameters)
	{
		if($callback instanceof Closure)
		{
			return $this->createCallableCallback($callback, $parameters);
		}
		elseif(is_string($callback))
		{
			return $this->createStringCallback($callback, $parameters);
		}
		else
		{
			return null;
		}
	}

	protected function createStringCallback($callback, $parameters)
	{
		if(function_exists($callback))
		{
			return $this->createCallableCallback($callback, $parameters);
		}
		else
		{
			return $this->createClassesCallback($callback, $parameters);
		}
	}

	protected function createCallableCallback($callback, $parameters)
	{	
		return call_user_func_array($callback, $parameters);
	}

	protected function createClassesCallback($callback, $parameters)
	{
		list($className, $method) = Str::parseCallback($callback, 'register');
	
		$instance = $this->container->make($className);

		$callable = array($instance, $method);

		return $this->createCallableCallback($callable, $parameters);
	}

	public function group($name, array $widgets)
	{
		$this->groups[$name] = $widgets;

		$this->registerBlade($name);
	}

	public function hasGroup($name)
	{
		return array_key_exists($name, $this->groups);
	}

	public function callGroup($name, $parameters = array())
	{
		if( ! $this->hasGroup($name)) return null;

		$result = '';

		foreach ($this->groups[$name] as $key => $widget)
		{
			$result .= $this->get($widget, array_get($parameters, $key, array()));	
		}

		return $result;
	}

	public function __call($method, $parameters = array())
	{
		return $this->get($method, $parameters);
	}
} 