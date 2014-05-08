<?php namespace Pingpong\Menus;

class MenuItem
{	
	protected $attributes;

	protected $childs = array();

	protected $properties = array('url', 'route', 'title', 'name', 'icon', 'parent');
	
	public function __construct($attributes = array())
	{
		$this->attributes = $attributes;
		$this->setProperty($attributes);
	}

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

	public function child($attributes = array())
	{
		$this->childs[] = new self($attributes);
		return $this;	
	}

	public function addDivider()
	{
		$this->childs[] = new self(['name' => 'divider']);
		return $this;
	}

	public function getChilds()
	{
		return $this->childs;
	}

	public function getUrl()
	{
		return ! is_null($this->route) ? route($this->route) : url($this->url);
	}

	public function getRequest()
	{
		return ltrim(str_replace(url(), '', $this->getUrl()), '/');
	}

	public function getIcon($default = null)
	{
		return ! is_null($this->icon) ? '<i class="'. $this->icon .'"></i>' : $default;
	}

	public function isDivider()
	{
		return $this->name == 'divider';
	}

	public function hasSubMenu()
	{
		return ! empty($this->childs);
	}

	public function __get($key)
	{
		return isset($this->$key) ? $this->$key : null;
	}
}