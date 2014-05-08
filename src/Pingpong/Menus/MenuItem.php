<?php namespace Pingpong\Menus;

class MenuItem
{	
	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var array
	 */
	protected $childs = array();

	/**
	 * @var array
	 */
	protected $properties = array('url', 'route', 'title', 'name', 'icon', 'parent');
	
	/**
	 * Constructor.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function __construct($attributes = array())
	{
		$this->attributes = $attributes;
		$this->setProperty($attributes);
	}

	/**
	 * Set Property.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function setProperty($attributes)
	{
		foreach ($attributes as $key => $value)
		{
			if(in_array($key, $this->properties))
			{
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Add new child menu.
	 *
	 * @param  array  $attributes
	 * @return self
	 */
	public function child($attributes = array())
	{
		$this->childs[] = new self($attributes);
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
		return ! is_null($this->route) ? route($this->route) : url($this->url);
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
	 * Check is the current item divider.
	 *
	 * @return boolean
	 */
	public function isDivider()
	{
		return $this->name == 'divider';
	}

	/**
	 * Check is the current item has submenu .
	 *
	 * @return boolean
	 */
	public function hasSubMenu()
	{
		return ! empty($this->childs);
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