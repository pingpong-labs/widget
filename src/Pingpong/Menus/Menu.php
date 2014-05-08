<?php namespace Pingpong\Menus;

use Closure;

class Menu
{
	protected $menus = array();

	public function make($name)
	{
		$builder 			= new Builder($name);
		$this->menus[$name] = $builder;
		return $builder;
	}

	public function create($name, Closure $resolver)
	{
		$menus = $this->make($name);
		return $resolver($menus);
	}

	public function has($name)
	{
		return array_key_exists($name, $this->menus);
	}

	public function render($name, $presenter = null)
	{
		return $this->has($name) ? $this->menus[$name]->render($presenter) : null;
	}
}