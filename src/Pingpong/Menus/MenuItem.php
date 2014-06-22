<?php namespace Pingpong\Menus;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Facades\HTML;

class MenuItem implements ArrayableInterface
{	
	/**
	 * @var array
	 */
	protected $properties;

	/**
	 * @var array
	 */
	protected $childs = array();

	/**
     * The fillable attribute.
     *
	 * @var array
	 */
	protected $fillable = array('url', 'route', 'title', 'name', 'icon', 'parent', 'attributes');

	/**
	 * Constructor.
	 *
	 * @param array $properties
	 */
	public function __construct($properties = array())
	{
		$this->properties = $properties;
		$this->fill($properties);
	}

    /**
     * Create new static instance.
     *
     * @param array $attributes
     * @return static
     */
    public static function make(array $attributes)
    {
        return new static($attributes);
    }

	/**
	 * Set Property.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function fill($attributes)
	{
		foreach ($attributes as $key => $value)
		{
			if(in_array($key, $this->fillable))
			{
				$this->{$key} = $value;
			}
		}
	}

    /**
     * Create new menu child item using array.
     *
     * @param $attributes
     * @return $this
     */
    public function child($attributes)
    {
        $this->childs[] = new self($attributes);

        return $this;
	}

    /**
     * Register new child menu with dropdown.
     *
     * @param $title
     * @param callable $callback
     * @return $this
     */
    public function dropdown($title, \Closure $callback)
    {
        $child = static::make(compact('title'));

        call_user_func($callback, $child);

        $this->childs[] = $child;

        return $this;
    }

    /**
     * Create new menu item and set the action to route.
     *
     * @param $route
     * @param $title
     * @param array $parameters
     * @param array $attributes
     * @return array
     */
    public function route($route, $title, $parameters = array(), $attributes = array())
    {
        $item = array(
            'route'         =>  array($route, $parameters),
            'title'         =>  $title,
            'attributes'    =>  $attributes
        );

        $this->childs[] = new self($item);

        return $this;
    }

    /**
     * Create new menu item  and set the action to url.
     *
     * @param $url
     * @param $title
     * @param array $attributes
     * @return array
     */
    public function url($url, $title, $attributes = array())
    {
        $item = array(
            'url'        =>  $url,
            'title'      =>  $title,
            'attributes' =>  $attributes
        );

        $this->childs[] = new self($item);

        return $this;
    }

    /**
	 * Add new divider.
	 *
	 * @return self
	 */
	public function addDivider()
	{
		$this->childs[] = new self(['name' => 'divider']);
		return $this;
	}

	/**
	 * Get childs.
	 *
	 * @return array
	 */
	public function getChilds()
	{
		return $this->childs;
	}

	/**
	 * Get url.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return ! empty($this->route) ? route($this->route[0], $this->route[1]) : url($this->url);
	}

	/**
	 * Get request url.
	 *
	 * @return string
	 */
	public function getRequest()
	{
		return ltrim(str_replace(url(), '', $this->getUrl()), '/');
	}

	/**
	 * Get icon.
	 *
	 * @param  null|string $default
	 * @return string
	 */
	public function getIcon($default = null)
	{
		return ! is_null($this->icon) ? '<i class="'. $this->icon .'"></i>' : $default;
	}

    /**
     * Get properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get HTML attribute data.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return HTML::attributes($this->attributes);
    }

	/**
	 * Check is the current item divider.
	 *
	 * @return boolean
	 */
	public function isDivider()
	{
		return $this->name == 'divider';
	}

	/**
	 * Check is the current item has sub menu .
	 *
	 * @return boolean
	 */
	public function hasSubMenu()
	{
		return ! empty($this->childs);
	}

    /**
     * Same with hasSubMenu.
     *
     * @return bool
     */
    public function hasChilds()
    {
        return $this->hasSubMenu();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
	 * Get property.
	 *
	 * @param  string  $key
	 * @return string|null
	 */
	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : null;
	}
}