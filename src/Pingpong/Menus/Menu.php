<?php namespace Pingpong\Menus;

use Closure;
use Countable;
use Illuminate\View\Factory;
use Illuminate\Config\Repository;

/**
 * Class Menu
 * @package Pingpong\Menus
 */
class Menu implements Countable
{
	/**
     * The menus collections.
     *
	 * @var array
	 */
	protected $menus = array();

	/**
	 * The constructor.
	 * 
	 * @param Factory    $views  
	 * @param Repository $config 
	 */
	public function __construct(Factory $views, Repository $config)
	{
		$this->views  = $views;	
		$this->config = $config;	
	}

	/**
	 * Make new menu.
	 *
	 * @param  string $name 
	 * @return \Pingpong\Menus\Builder
	 */
	public function make($name)
	{
		$builder = new Builder($name, $this->config);

        $this->menus[$name] = $builder;

		return $builder;
	}

	/**
	 * Create new menu.
	 *
     * @param  string $name
     * @param  Callable $resolver
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
	 * @return string|null
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
	 * @return string|null
	 */
	public function get($name, $presenter = null)
	{
		return $this->has($name) ? $this->menus[$name]->render($presenter) : null;
	}

    /**
     * Render the menu tag by given name.
     *
     * @param $name
     * @param null $presenter
     * @return string
     */
    public function render($name, $presenter = null)
	{
		return $this->get($name, $presenter);
	}

    /**
     * Get a stylesheet for enable multilevel menu.
     *
     * @return mixed
     */
    public function style()
    {
        return $this->views->make('menus::style')->render();
    }

    /**
     * Get all menus.
     *
     * @return array
     */
    public function all()
    {
        return $this->menus;
    }

    /**
     * Get count from all menus.
     * 
     * @return int 
     */
    public function count()
    {
    	return count($this->menus);
    }

    /**
     * Empty the current menus.
     * 
     * @return void 
     */
    public function destroy()
    {
        $this->menus = array();
    }
    
}