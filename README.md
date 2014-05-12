Laravel 4 - Simple Menus
========================

### Readme
 	
[Klik disini](https://github.com/pingpong-labs/menus/blob/master/README-id.md) untuk membaca readme dalam Bahasa Indonesia.

### Installation

First, open your `composer.json` file and add new package.
```
    "require": {
        "pingpong/menus": "dev-master" 
    },
```
Then open a terminal and run:
```
composer update 
```
After that, open the file `app/config/app.php` and add a new service provider in `providers` array.
```php
   
	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',		
		...		
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		
		// here
		'Pingpong\Menus\MenusServiceProvider'

	),
```
Then add the class alias ini `aliases`.
```php

	'aliases' => array(

		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',		
		...		
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',
		
		// here
		'Menu'          =>  'Pingpong\Menus\Facades\Menu',
	)
```
Then, publish configuration for package `pingpong/menus`:
```
php artisan config:publish pingpong/menus
```
Done.

### Example Usage

First, create a file called `menus.php` in your `app/` folder, alongside with `routes.php` and `filters.php`. The file will be automatically include if the file exists. And you can define your menus in that file.

**Creating a menu.**
```php
Menu::create('navbar', function($menu)
{
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
	]);
	$menu->add([
		'url'	=>	'pages/about-me',
		'title'	=>	'About Me',
		'icon'  =>  'fa fa-user'
	]);
	$menu->add([
			'url'	=>	'#',
			'title'	=>	'Category',
		])->child([
			'title' => 	'Sport',
			'url'	=>	'category/sport'
	   	])->child([
			'title' => 	'Business',
			'url'	=>	'category/business'
	   	])->child([
			'title' => 	'Travel',
			'url'	=>	'category/travel'
	   	]);
});
````

**Make Lots of menu**

This package allows you to create a menu with a lot of different styles. Here's an example.

```php
Menu::create('menu1', function($menu)
{
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
	]);
})

Menu::create('menu2', function($menu)
{
	$menu->add([
		'url'	=>	'home',
		'title'	=>	'Dashboard',
	]);
})
```

**Calling a menu.**

To call up the menu you can use `render` or `get` method.

```php
Menu::render('navbar');

Menu::get('menu1');

Menu::get('menu2');
```

**Menu Style.**

By default the generated menu style is bootstrap navbar. In addition there are also several different menu styles and is already available by default are `navbar`, `navbar-right`, `nav-pills` and `nav-tab`. To set the style menu you can use the method `style`. Examples like this.

```php
Menu::create('navbar', function($menu)
{
	// secara default mendukung 4 style : navbar, navbar-right, nav-pills dan nav-tab
	$menu->style('nav-pills');
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
	])->add([
		'url'	=>	'pages/about-me',
		'title'	=>	'About Me',
	]);
});
```

**Make A Costum Presenter**

You can create your own presenter class. Make sure your presenter is extends to `Pingpong\Menus\Presenters\Presenter`, that class is also `implements` to 'Pingpong\Menus\Presenters\PresenterInterface'. For example this is zurb topbar presenter. 

```php

use Pingpong\Menus\Presenters\Presenter;

class ZurbTopBarPresenter extends Presenter
{
	/**
	 * {@inheritdoc }
	 */
	public function getOpenTagWrapper()
	{
		return  PHP_EOL . '<section class="top-bar-section">' . PHP_EOL;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getCloseTagWrapper()
	{
		return  PHP_EOL . '</section>' . PHP_EOL;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getMenuWithoutDropdownWrapper($item)
	{
		return '<li'.$this->getActiveState($item).'><a href="'. $item->getUrl() .'">'.$item->getIcon().' '.$item->title.'</a></li>';
	}

	/**
	 * {@inheritdoc }
	 */
	public function getActiveState($item)
	{
		return \Request::is($item->getRequest()) ? ' class="active"' : null;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getDividerWrapper()
	{
		return '<li class="divider"></li>';
	}

	/**
	 * {@inheritdoc }
	 */
	public function getMenuWithDropDownWrapper($item)
	{
		return '<li class="has-dropdown">
		        <a href="#">
		         '.$item->getIcon().' '.$item->title.'
		        </a>
		        <ul class="dropdown">
		          '.$this->getChildMenuItems($item).'
		        </ul>
		      </li>' . PHP_EOL;
		;
	}
}

```
For use costum presenter, you can use the `setPresenter` method, for example like this.
```php
Menu::create('zurb-top-bar', function($menu)
{
	$menu->setPresenter('ZurbTopBarPresenter');
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
	])->add([
		'url'	=>	'pages/about-me',
		'title'	=>	'About Me',
	]);
});
```

Or you can set it at the time of calling the menu, like this.

```php
Menu::render('zurb-top-bar', 'ZurbTopBarPresenter');
```

**Register A New Style Menu**

This Style is like an alias to a presenter. You can register your style from your costum presenter in the configuration file in  `app/config/packages/pingpong/menus/config.php`. Like this.

```php
return array(
	'navbar'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter',
	'navbar-right'	=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarRightPresenter',
	'nav-pills'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavPillsPresenter',
	'nav-tab'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavTabPresenter',

	'zurb-top-bar'	=>	'ZurbTopBarPresenter',
);
```

Then you can use a style like this. Same as section **Menu Style** above.

```php
Menu::create('zurb-top-bar', function($menu)
{
	$menu->style('zurb-top-bar');
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
	]);
});
```

**Get The Menu Instance**

To get an instance of an existing menu, you can use the `instance` method. Here's an example.

```php
$menu = Menu::instance('zurb-top-bar');

// You can also make additions to the menu again

$menu->add(['title' => 'Settings', 'route' => 'settings']);
```

### License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)