Laravel 4 - Simple Menus
========================

### Instalation

### Usage

```php
Menu::create('navbar', function($menu)
{
	$menu->add([
		'url'	=>	'settings',
		'title'	=>	'Settings',
	])->child([
		'title'	=>	'Account',
		'url'	=>	'admin/settings/account'
	])->child([
		'title'	=>	'Themes',
		'url'	=>	'admin/settings/themes'
	])->child([
		'title'	=>	'Posts',
		'url'	=>	'admin/settings/posts'
	])->addDivider()
	  ->child([
	  	'title'	=>	'Logout',
	  	'url'	=>	'admin-logout'
	]);
});
````

### License