<?php namespace Pingpong\Widget;

use Str;
use Blade;
use Closure;
use ReflectionFunction;
use Pingpong\Widget\WidgetException as Exception;

class Widget
{
	/**
	 * @var $widgets 
	 */
	protected $widgets  = array();

	/**
	 * @var $groups 
	 */
	protected $groups = array();

	/**
	 * Get all widgets.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->widgets;
	}

	/**
	 * Get all widget groups.
	 *
	 * @return array
	 */
	public function groups()
	{
		return $this->groups;
	}

	/**
	 * Register new widget group.
	 *
	 * @param  string $name
	 * @param  array  $widgets
	 * @return void
	 */
	public function group($name, $widgets = array())
	{
		$this->groups[$name] = $widgets;

		Blade::extend(function($view) use($name){
			return preg_replace("/@$name\((.*)\)/", "<?php echo Widget::$name($1); ?>", $view);
		});		
	}

	/**
	 * Register new widget.
	 *
	 * @param  string   $name
	 * @param  CLosure  $callback
	 * @return void
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
		return $this;
	}

	/**
	 * Determine if widget exists.
	 *
	 * @param  string $name
	 * @return boolean
	 */
	public function has($name)
	{
		return isset($this->widgets[$name]);
	}

	/**
	 * Determine if widget group exists.
	 *
	 * @param  string $group.
	 * @return boolean
	 */
	public function hasGroup($group)
	{
		return isset($this->groups[$group]);
	}

	/**
	 * Determine if the closure have parameters.
	 *
	 * @param  Closure $callback
	 * @return boolean
	 */
	protected function hasParams(Closure $callback)
	{
		$rf = new ReflectionFunction($callback);
		$params =  $rf->getParameters();
		return ! empty($params);
	}
	
	/**
	 * Get widget by given name.
	 *
	 * @param  string $name
	 * @param  array  $params
	 * @return mixed
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

	/**
	 * Get widget group by given name.
	 *
	 * @param  string $name
	 * @param  array  $params
	 * @return mixed
	 */
	public function callWidget($name, $params = array())
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

	/**
	 * Get widget group by given name.
	 *
	 * @param  string $name
	 * @param  array  $params
	 * @return mixed
	 */
	public function callGroup($group, $params = array())
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

	/**
	 * Magic call widget and widget group.
	 *
	 * @param  string $method
	 * @param  array  $args
	 * @return mixed
	 */
	public function __call($method, $args = array())
	{
		return $this->get($method, $args);
	}
}
