Laravel 4 - Simple Menus
========================

### Readme
 	
[Klik disini](https://github.com/pingpong-labs/menus/blob/master/README-id.md) untuk membaca readme dalam Bahasa Indonesia.

### Installation

First, open your `composer.json` file and add new package.
```
    "require": {
        "pingpong/menus": "1.0.*" 
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
Then add the class alias in `aliases`.
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

**NEW!**

Status : Under Development

Branch : `dev-master`

On `app/menus.php` :
```php

// app/menus.php

use Pingpong\Menus\Builder;
use Pingpong\Menus\MenuItem;

Menu::create('top', function(Builder $menu)
{
    // simple using route
    $menu->route('home', 'Home');
    // simple using route with parameters and attributes
    $menu->route('profile.user', 'View Profile', ['username' => 'gravitano'], ['class' => 'btn btn-default']);
    // using array
    $menu->add([
        'url'   =>  'messages',
        'title' =>  'Messages',
        'icon'  =>  'fa fa-envelope'
    ]);
    // using url
    $menu->url('products', 'Products');
    // using url with attributes
    $menu->url('products/1', 'View Products', ['class' => 'btn btn-link']);
    // new! support dropdown with multi level nested menu
    $menu->dropdown('Settings', function(MenuItem $sub)
    {
        $sub->url('profile/edit', 'Edit Profile');
        $sub->dropdown('Account', function(MenuItem $sub)
        {
            $sub->url('settings/payment', 'Payment');
            // nested menu
            $sub->dropdown('Social Network', function(MenuItem $sub)
            {
                $sub->url('https://github.com/gravitano', 'Github', ['target' => '_blank']);
                $sub->url('https://facebook.com/warsono.m.faisyal', 'Facebook', ['target' => '_blank']);
                $sub->url('https://twitter.com/gravitano', 'Twitter', ['target' => '_blank']);
            });
        });
        $sub->url('logout', 'Logout');
    });
});
```

On view, for example `hello.blade.php`.
```html
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	{{ HTML::style('css/bootstrap.css') }}
	{{-- Add new style for allowing multi level menu --}}
    {{ Menu::style() }}
</head>
<body>

    <div class="container">
        {{ Menu::get('top') }}
    </div>

    {{ HTML::script('js/jquery.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>
```

First, create a file called `menus.php` in your `app/` folder, alongside with `routes.php` and `filters.php`. The file will be automatically include if the file exists. And you can define your menus in that file.

**Creating a menu.**
```php
Menu::create('navbar', function($menu)
{
    // using array
	$menu->add([
		'route'	=>	'home',
		'title'	=>	'Home',
		'icon'  =>  'fa fa-dashboard'
	]);

	// menu with target to url
	$menu->url('/', 'Home');

	// with additional attributes
	$menu->url('/', 'Home', ['class' => 'nav-link']);

	// menu with target to registered route
	$menu->route('home', 'Home');

	// with additional route parameters and attributes
	$menu->route('home', 'Home', null, ['class' => 'nav-link']);

	$menu->route('users.show', Auth::user()->name, Auth::id(), ['class' => 'nav-link']);

	$menu->route('users.show', 'My Profile', ['username' => 'gravitano'], ['class' => 'nav-link']);

	$menu->route('products.show', 'View Product', 1, ['class' => 'nav-link']);

	// dropdown menu
	$menu->dropdown('Settings', function($sub)
	{
	    $sub->url('settings/account', 'Account');
	    $sub->route('settings.profile', 'Profile');
	    $sub->route('logout', 'Logout');
	});

	// multi level menu (nested)
	$menu->dropdown('Category', function($sub)
	{
	    $sub->url('category/programming', 'Programming');

	    $sub->url('category/screencasts', 'Screencasts');

	    $sub->dropdown('Sport News', function($sub)
	    {
	        $sub->url('category/football', 'Football');
	        $sub->url('category/basket-ball', 'Basket Ball');
	    });

	    $sub->dropdown('Title', function($sub)
	    {
	        $sub->url('link', 'Link');
	        $sub->dropdown('Title', function($sub)
	        {
	            $sub->dropdown('Title N', function($sub)
	            {
	                // more nested menu here
	            });
	        });
	    });
	});
});
````

**Make Lots of menu**

This package allows you to create a menu with a lot of different styles. Here's an example.

```php
Menu::create('menu1', function($menu)
{

	$menu->route('home', 'Home');

	$menu->url('profile', 'Profile');
});

Menu::create('menu2', function($menu)
{
	$menu->route('home', 'Home');

	$menu->url('profile', 'Profile');
});
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
	$menu->style('nav-pills');

	$menu->route('home', 'Home');

	$menu->url('profile', 'Profile');
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

	$menu->route('home', 'Home');

	$menu->url('profile', 'Profile');
});
```

Or you can set it at the time of calling the menu, like this.

```php
Menu::render('zurb-top-bar', 'ZurbTopBarPresenter');

Menu::get('zurb-top-bar', 'ZurbTopBarPresenter');
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
	$menu->url('profile', 'Profile');

	$menu->route('settings', 'Settings');
});
```

**Get The Menu Instance**

To get an instance of an existing menu, you can use the `instance` method. Here's an example.

```php
$menu = Menu::instance('zurb-top-bar');

// You can also make additions to the menu again

$menu->add(['title' => 'Settings', 'route' => 'settings']);

$menu->url('profile', 'Profile');

$menu->route('settings', 'Settings');
```

### License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
