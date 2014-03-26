Simple Widget System - Laravel 4.*
======

Simple widget system for create awesome feature on blade templating Laravel 4

Installation
-
### Installation
Open your composer.json file, and add the new required package.

```
  "pingpong/widget": "1.0.0" 
```

Next, open a terminal and run.

```
  composer update 
```

After the composer updated. Add new service provider in app/config/app.php.

```
  'Pingpong\Widget\WidgetServiceProvider'
```

Done.

# Example 

Registering your widget
----------------------------------

Simple Widget :
```php

Widget::register('awesome', function(){

	return View::make('awesome');

});
```
Widgets with one or more parameters:

```php

Widget::register('hello', function($name){

	return "Hello, $name !";

});

Widget::register('box', function($title, $description){

	return View::make('widgets.box', compact('title', 'description'));

});

```

Widget grouping of widgets that have previously been defined.

```php

// First, you must registering one or more widget

Widget::register('categories', function(){
	return View::make('widgets.categories');
});

Widget::register('latestPost', function(){
	return View::make('widgets.latestPost');
});

// Next, you can group some widgets like this:

Widget::group('sidebar', array('categories', 'latestPost'));

Widget::group('footer', array('hello', 'box'));

```

Calling your widget 
---------------------------------

Globally calling the widget just like below:
```php

Widget::awesome();

Widget::hello('Jhon');

Widget::box('Latest News', 'This is a description of latest news');

// calling widget group just like below
// Widget::$name();
Widget::sidebar();

// calling widget group which parameters just like below
// Widget::$name($params1, $params2, $params3, ....);
Widget::box(array('name'), array('My Tweets', '.....Latest Tweets'));

```
simple calling widget on view :

```php

@awesome

@hello('John')

//calling group widget

@sidebar()

@box(array('name'), array('My Tweets', '.....Latest Tweets'))

```
#Changes 

Version 1.0
----------

- Adding new features. Widgets :: group. Group is used to group some widgets like the widget to the sidebar, footer or other.
- Removing the slash as a differentiator parameter, and replace it with an array of features and / or coma, like calling php function generally.
- Adding a new class WidgetException.
- Changing the structure becomes more flexible.

#Developer

[Gravitano](https://github.com/gravitano)
