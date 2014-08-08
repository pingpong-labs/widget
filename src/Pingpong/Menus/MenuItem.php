<?php namespace Pingpong\Menus;

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Contracts\ArrayableInterface;

class MenuItem implements ArrayableInterface
{
    /**
     * Array properties.
     *
	 * @var array
	 */
	protected $properties;

	/**
     * The child collections for current menu item.
     *
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
     * Set the icon property when the icon is defined in the link attributes.
     *
     * @param array $properties
     * @return array
     */
    protected static function setIconIfDefinedInAttributes(array $properties)
    {
        $icon = array_get($properties, 'attributes.icon');
        if ( ! is_null($icon))
        {
            $properties['icon'] = $icon;

            array_forget($properties, 'attributes.icon');

            return $properties;
        }
        return $properties;
    }

    /**
     * Get random name.
     *
     * @param array $attributes
     * @return string
     */
    protected static function getRandomName(array $attributes)
    {
        return substr(md5(array_get($attributes, 'title', str_random(6))), 0, 5);
    }

    /**
     * Create new static instance.
     *
     * @param array $properties
     * @return static
     */
    public static function make(array $properties)
    {
        $properties = self::setIconIfDefinedInAttributes($properties);

        return new static($properties);
    }

	/**
	 * Fill the attributes.
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
        $this->childs[] = static::make($attributes);

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
            'attributes'    =>  $attributes,
        );

        $this->childs[] = static::make($item);

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

        $this->childs[] = static::make($item);

        return $this;
    }

    /**
	 * Add new divider.
	 *
	 * @return self
	 */
	public function addDivider()
	{
		$this->childs[] = static::make(array('name' => 'divider'));

		return $this;
	}

    /**
     * Alias method instead "addDivider".
     *
     * @return MenuItem
     */
    public function divider()
    {
        return $this->addDivider();
    }

    /**
     * Add dropdown header.
     *
     * @param $title
     * @return $this
     */
    public function addHeader($title)
    {
        $this->childs[] = static::make(array(
            'name'  =>  'header',
            'title' =>  $title
        ));

        return $this;
    }

    /**
     * Same with "addHeader" method.
     *
     * @param $title
     * @return $this
     */
    public function header($title)
    {
        return $this->addHeader($title);
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
		return $this->is('divider');
	}

    /**
     * Check is the current item divider.
     *
     * @return bool
     */
    public function isHeader()
    {
        return $this->is('header');
    }

    /**
     * Check is the current item divider.
     *
     * @param $name
     * @return bool
     */
    public function is($name)
    {
        return $this->name == $name;
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
     * Check the active state for current menu.
     *
     * @return mixed
     */
    public function hasActiveOnChild()
    {
        if($this->hasChilds())
        {
            foreach($this->getChilds() as $child)
            {
                if($child->hasRoute() && $child->getActiveStateFromRoute())
                {
                    return true;
                }
                elseif($child->getActiveStateFromUrl())
                {
                    return true;
                }
                return false;
            }
        }
        return false;
    }

    /**
     * Get active state for current item.
     *
     * @return mixed
     */
    public function isActive()
    {
        if ($this->hasRoute())
        {
            return $this->getActiveStateFromRoute();
        }
        else
        {
            return $this->getActiveStateFromUrl();
        }
    }

    /**
     * Determine the current item using route.
     *
     * @return bool
     */
    protected function hasRoute()
    {
        return ! empty($this->route);
    }

    /**
     * Get active status using route.
     *
     * @return bool
     */
    protected function getActiveStateFromRoute()
    {
        return Route::is($this->route[0]);
    }

    /**
     * Get active status using request url.
     *
     * @return bool
     */
    protected function getActiveStateFromUrl()
    {
        return Request::is($this->url);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getProperties();
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