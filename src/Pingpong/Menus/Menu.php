<?php namespace Pingpong\Menus;

use Closure;

class Menu
{
	/**
	 * @var array
	 */
	protected $menus = array();

	/**
	 * Make new menu.
	 *
	 * @param  string $name 
	 * @return \Pingpong\Menus\Builder
	 */
	public function make($name)
	{
		$builder 			= new Builder($name);
		$this->menus[$name] = $builder;
		return $builder;
	}

	/**
	 * Create new menu.
	 *
	 * @param  string $name 
	 * @return \Pingpong\Menus\Builder
	 */
	public function create($name, Closure $resolver)
	{
		$menus = $this->make($name);
		return $resolver($menus);
	}

	/**
	 * Check if the menu exists.
	 *
	 * @param  string $name 
	 * @return boolean
	 */
	public function has($name)
	{
		return array_key_exists($name, $this->menus);
	}

	/**
	 * Get instance of the given menu if exists.
	 * 
	 * @param  string $name 
	 * @return string
	 */
	public function instance($name)
	{
		return $this->has($name) ? $this->menus[$name] : null;
	}

	/**
	 * Render the menu tag by given name.
	 * 
	 * @param  string $name 
	 * @param  string $presenter 
	 * @return string
	 */
	public function get($name, $presenter = null)
	{
		return $this->has($name) ? $this->menus[$name]->render($presenter) : null;
	}

	/**
	 * Render the menu tag by given name.
	 * 
	 * @param  string $name 
	 * @return string
	 */
	public function render($name, $presenter = null)
	{
		return $this->get($name, $presenter);
	}
}