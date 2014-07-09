<?php namespace Pingpong\Widget;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Closure;
use Pingpong\Widget\WidgetException as Exception;
use Illuminate\Container\Container;

class Widget
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
	 * @var $widgets
	 */
	protected $widgets  = array();

	/**
	 * @var $groups 
	 */
	protected $groups = array();

    /**
     * Create a new instance and inject IoC container.
     *
     * @param  \Illuminate\Container\Container $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: new Container;
    }

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

        if (!getenv('SKIP_BLADE')) {

            Blade::extend(function($view) use($name){
                return preg_replace("/@$name\((.*)\)/", "<?php echo Widget::$name($1); ?>", $view);
            });
        }
	}

	/**
	 * Register new widget.
	 *
	 * @param  string   $name
	 * @param  mixed  $callback
	 * @return void
	 */
	public function register($name, $callback)
	{
		$this->widgets[$name] 	= array(
			'name'		=>	Str::slug($name, "_"),
			'callback'	=>	$this->makeCallback($callback)
		);

        if (!getenv('SKIP_BLADE')) {

            Blade::extend(function($view) use($name){
                return preg_replace("/@$name\((.*)\)/", "<?php echo Widget::$name($1); ?>", $view);
            });
        }

		return $this;
	}

    /**
     * Make callback from input
     *
     * @param $callback
     * @return mixed
     */
    public function makeCallback($callback)
    {
        if (is_string($callback))
        {
            $callback = $this->createClassCallback($callback);
        }

        return $callback;
    }

    /**
     * Create a class based callback using the IoC container.
     *
     * @param  mixed    $callback
     * @return \Closure
     */
    public function createClassCallback($callback)
    {
        $container = $this->container;

        return function() use ($callback, $container)
        {
            // If the callback has an @ sign, we will assume it is being used to delimit
            // the class name from the handle method name. This allows for handlers
            // to run multiple handler methods in a single class for convenience.
            $segments = explode('@', $callback);

            $method = count($segments) == 2 ? $segments[1] : 'handle';

            $callable = array($container->make($segments[0]), $method);

            // We will make a callable of the listener instance and a method that should
            // be called on that instance, then we will pass in the arguments that we
            // received in this method into this listener class instance's methods.
            $data = func_get_args();

            return call_user_func_array($callable, $data);
        };
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
