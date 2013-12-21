<?php namespace Pingpong\Widget;

class Widget
{
	protected $w;

	function __construct() {
		
	}

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

	public function register($name, $action)
	{	
		$this->w[$name] = array(
			'name'		=>	$name,
			'action'	=>	$action
		);
	}

	public function get($name)
	{
		if(isset($this->w[$name]))
		{
			$w = $this->w[$name];
			$action = $w['action'];
			if(is_callable($action))
			{
				return call_user_func($action);
			}
			return $action;
		}
		return 'Undefined widget "'. $name.'".';
	}

	public function has($name)
	{
		return isset($this->w[$name]);
	}

	public function __call($method, $args = array())
	{		
		if($this->has($method))
		{
			return $this->get($method);
		}
		return 'Call : Undefined widget "'.$method.'"';
	}
}