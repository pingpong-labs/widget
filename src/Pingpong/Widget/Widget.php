<?php

namespace Pingpong\Widget;

use Blade;
use Closure;
use Pingpong\Widget\WidgetException as Exception;
use Str;
use ReflectionFunction;

class Widget
{
	/*
	 |-------------------------------------------------------------------
	 |	Property widgets
	 |-------------------------------------------------------------------
	 |	Here is all widgets is registered
	 |
	 */
	protected $widgets  = array();;

	/*
	 |-------------------------------------------------------------------
	 |	property grouping the widgets
	 |-------------------------------------------------------------------
	 |	Here is all group of widgets is registered
	 |
	 */
	protected $groups = array();

	/*
	 |-------------------------------------------------------------------
	 |	All widgets
	 |-------------------------------------------------------------------
	 |	returning all registered widgets
	 |
	 */
	public function all()
	{
		return $this->widgets;
	}

	/*
	 |-------------------------------------------------------------------
	 |	All widget groups
	 |-------------------------------------------------------------------
	 |	returning all registered widget groups
	 |
	 */
	public function groups()
	{
		return $this->groups;
	}

	/*
	 |-------------------------------------------------------------------
	 |	Grouping the widgets
	 |-------------------------------------------------------------------
	 |	Here is you can grouping the widget like Sidebar, Footer or other
	 |
	 */
	public function group($name, $widgets = array())
	{
		$this->groups[$name] = $widgets;

		Blade::extend(function($view) use($name){
			return preg_replace("/@$name\((.*)\)/", "<?php echo Widget::$name($1); ?>", $view);
		});		
	}

	/*
	 |-------------------------------------------------------------------
	 |	Registering the widget
	 |-------------------------------------------------------------------
	 |	Here is method for registering new widget
	 |
	 */
	public function register($name, Closure $callback)
	{
		$this->widgets[$name] 	= array(
			'name'		=>	Str::slug($name, "_"),
			'callback'	=>	$callback
		);

		if($this->hasParams($callback))
		{
			Blade::extend(function($view) use($name){
				return preg_replace("/@$name(.*)/", "<?php echo Widget::$name$1; ?>", $view);
			});
		}else
		{
			Blade::extend(function($view) use($name){
				return preg_replace("/@$name/", "<?php echo Widget::$name(); ?>", $view);
			});			
		}
		return $this->widgets[$name];
	}

	/*
	 |-------------------------------------------------------------------
	 |	is there a widget ?
	 |-------------------------------------------------------------------
	 |	return TRUE if widget is defined and otherwise.
	 |
	 */
	public function has($name)
	{
		return isset($this->widgets[$name]);
	}

	/*
	 |-------------------------------------------------------------------
	 |	is specific widget group defined ?
	 |-------------------------------------------------------------------
	 |	return TRUE if widget group is defined and otherwise.
	 |
	 */
	public function hasGroup($group)
	{
		return isset($this->groups[$group]);
	}

	/*
	 |-------------------------------------------------------------------
	 |	is widget has a parameter?
	 |-------------------------------------------------------------------
	 |	return TRUE if widget is has parameter and otherwise.
	 |
	 */
	public function hasParams(Closure $callback)
	{
		$rf = new ReflectionFunction($callback);
		$params =  $rf->getParameters();
		return ! empty($params);
	}
	

	/*
	 |-------------------------------------------------------------------
	 |	Getting the widget
	 |-------------------------------------------------------------------
	 |	Here is method for get the widget
	 |
	 */
	public function get($name, $params = array())
	{
		if($this->hasGroup($name))
		{
			return $this->callGroup($name, $params);
		}elseif($this->has($name))
		{
			return $this->callWidget($name, $params);
		}		
	}

	/*
	 |-------------------------------------------------------------------
	 |	Calling the widget
	 |-------------------------------------------------------------------
	 |	Here is method for calling the widget
	 |
	 */
	protected function callWidget($name, $params = array())
	{
		if( ! $this->has($name)) throw new Exception("Widget [$name] does not exists!");

		$callback = $this->widgets[$name]['callback'];
		if(is_string($callback))
		{
			return $callback;
		}elseif ($callback instanceof Closure)
		{
			return call_user_func_array($callback, $params);
		}else
		{
			throw new Exception("Callback for widget [$name] is not supported!");
		}
	}

	/*
	 |-------------------------------------------------------------------
	 |	Calling group widget
	 |-------------------------------------------------------------------
	 |	Here is method for group widget
	 |
	 */
	protected function callGroup($group, $params = array())
	{
		if( ! $this->hasGroup($group)) throw new Exception("Grouping widget [$group] does not exist");

		$group = $this->groups[$group];
		if(count($group) > 0)
		{
			foreach ($group as $key => $value) {
				$args = isset($params[$key]) ? $params[$key] : array();
				echo $this->callWidget($value, $args);
			}
		}
	}

	/*
	 |-------------------------------------------------------------------
	 |	Magic Method __call the widget
	 |-------------------------------------------------------------------
	 |	its allow for getting the widget by name using call method
	 |
	 */
	public function __call($method, $args = array())
	{
		return $this->get($method, $args);
	}
}
