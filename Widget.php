<?php namespace Pingpong\Widget;

use Blade;
use Closure;

class Widget
{
	/**
	 *------------------------------------------------------------
	 * Private the object 'w' as widgets
	 *------------------------------------------------------------
	 *
	 */
	private $w;

	/**
	 *------------------------------------------------------------
	 * Show all widget, with or without dump
	 *------------------------------------------------------------
	 *
	 */
	public function all($dump = FALSE)
	{
		$w = $this->w;
		if($dump === true)
		{
			echo "<pre>";
			print_r($w);
			echo "</pre>";
			return;
		}
		return $w;
	}

	/**
	 *------------------------------------------------------------
	 * Registering new widget
	 *------------------------------------------------------------
	 *
	 */
	public function register($name, $action)
	{		
		// explode 
		$en = explode(":", $name);
		// get new name 
		$name = count($en) == 2 ? $en[1] : $name;
		// register new widget
		$this->w[$name] = array(
			'name'		=>	$name,
			'action'	=>	$action
		);

		// registering shortcode on view 
		if(count($en) == 2){
			Blade::extend(function ($view) use ($name) {
				$replacement = "<?php
					echo Widget::get('$name/$1');
				?>";
				return preg_replace("/\[$name:(.*?)]/", $replacement, $view);
			});		
		}else{
			Blade::extend(function ($view) use ($name) {
				$html = Widget::get($name);
				return str_replace("@$name", $html, $view);
			});		
		}
		return;
	}

	/**
	 *------------------------------------------------------------
	 * Get widget
	 *------------------------------------------------------------
	 *
	 */
	public function get($name)
	{
		$wparams = array();
		$exp = explode('/', $name);
		
		if(count($exp) > 1)
		{
			$wname 		= $exp[0];
			$exp = array_except($exp, 0);
			if(count($exp) > 0){
				foreach ($exp as $v) {
					$wparams[] = $v;
				}
			}
		}else{
			$wname = $name;
		}	

		if(isset($this->w[$wname]))
		{
			$w = $this->w[$wname];
			$action = $w['action'];
			if($action instanceof Closure)
			{
				return call_user_func_array($action, $wparams);
			}
			return $action;
		}
		return 'Undefined widget "'. $wname.'".';
	}

	/**
	 *------------------------------------------------------------
	 * isset ?
	 *------------------------------------------------------------
	 *
	 */
	public function has($name)
	{
		return isset($this->w[$name]);
	}

	/**
	 *------------------------------------------------------------
	 * Magic method call
	 *------------------------------------------------------------
	 *
	 */
	public function __call($method, $args = array())
	{
		if($this->has($method))
		{
			$widget = $method.'/'.implode('/', $args);
			return $this->get($widget);
		}
		return 'Call : Undefined widget "'.$method.'"';
	}

}