Laravel 4 - Simple Menus
========================

### Readme
 	
 	[Click here]() to read the readme in English.

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
Setelah itu, buka file `app/config/app.php` dan tambahkan new service provider dibagian array `providers`.
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
Kemudian, publish configuration untuk package `pingpong/menus`:
```
php artisan config:publish pingpong/menus
```
Selesai.
### Contoh Penggunaan

Pertama, buat file bernama `menus.php` didalam folder `app/` Anda, berdampingan dengan file `routes.php` dan `filters.php`. File tersebut akan otomatis di `include` jika file tersebut ada dan difile itulah Anda bisa mendefinisikan menu-menu yang akan Anda buat. 

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

**Membuat Banyak Menu**

Package ini memungkinkan Anda membuat banyak menu dengan style yang berbeda-beda. Berikut contohnya.

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

**Pemanggilan menu.**

Pemanggilan menu bisa menggunakan method `render` atau `get`.

```php
Menu::render('navbar');

Menu::get('menu1');

Menu::get('menu2');
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
});
```

**Membuat Costum Presenter**
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
});
```
Atau Anda bisa mensetnya pada saat pemanggilan menu tersebut seperti ini.
```php
Menu::render('zurb-top-bar', 'ZurbTopBarPresenter');
```

**Mendaftar Style Baru**

Style ini ibarat alias untuk sebuah presenter. Anda bisa mendaftarkan style dari costum presenter Anda dikonfigurasi file yaitu `app/config/packages/pingpong/menus/config.php`. Seperti ini.

```php
return array(
	'navbar'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarPresenter',
	'navbar-right'	=>	'Pingpong\Menus\Presenters\Bootstrap\NavbarRightPresenter',
	'nav-pills'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavPillsPresenter',
	'nav-tab'		=>	'Pingpong\Menus\Presenters\Bootstrap\NavTabPresenter',

	'zurb-top-bar'	=>	'ZurbTopBarPresenter',
);
```
Kemudian Anda bisa menggunakan style seperti ini. Sama seperti bagian **Menu Style** diatas.
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

**Mendapatkan Instance**

Untuk mendapatkan instance dari menu yang telah ada, Anda dapat menggunakan method `instance`. Berikut contohnya.

```php
$menu = Menu::instance('zurb-top-bar');

// Anda juga bisa melakukan penambahan menu (lagi)

$menu->add(['title' => 'Settings', 'route' => 'settings']);
```

### License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)