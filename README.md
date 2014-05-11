Laravel 4 - Simple Menus
========================

### Instalasi

Pertama, buka file `composer.json` Anda dan tambahkan package baru.
```
    "require": {
        "pingpong/menus": "dev-master" 
    },
```
Kemudian buka terminal dan jalankan:
```
composer update 
```
Setelah telah itu, buka file `app/config/app.php` dan tambahkan new service provider dibagian array `providers`.
```php
   
	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		
		...
		
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		
		// disini
		'Pingpong\Menus\MenusServiceProvider'

	),
```
Kemudian tambahkan juga class alias dibagian array `aliases`.

```php

	'aliases' => array(

		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		
		...
		
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',
		
		// disini
		'Menu'          =>  'Pingpong\Menus\Facades\Menu',
	)
```
Selesai.
### Contoh Penggunaan

Pertama, buat file bernama `menus.php` didalam folder `app/` Anda, berdampingan dengan file `routes.php` dan `filters.php`. Kemudian incude/require file tersebut kedalam file `app/start/global.php`.

**Membuat menu.**
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

**Pemanggilan menu.**
```php
Menu::render('navbar');
```

**Menu Style.**

Secara default style menu yang dihasilkan adalah bootstrap navbar. Selain itu ada juga beberapa style menu yang berbeda dan sudah tersedia secara default yaitu `navbar`, `navbar-right`, `nav-pills` dan `nav-tab`. Untuk men-set style menu Anda bisa menggunakan method `style`. Contohnya seperti ini.

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
```

**Membuat Costum Presenter**
```php
<?php

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
Untuk menggunakan costum presenter, Anda bisa menggunakan method `setPresenter`, contohnya seperti ini.
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
```
Atau Anda bisa mensetnya pada saat pemanggilan menu tersebut seperti ini.
```php
Menu::render('zurb-top-bar', 'ZurbTopBarPresenter');
```
### License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)