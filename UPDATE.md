#Registering your widget
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

```

#Calling your widget 
---------------------------------

```php

Widget::awesome();

Widget::hello('Jhon');

Widget::box('Latest News', 'This is a description of latest news');

// calling widget group just like below
// Widget::$name();
Widget::sidebar();

// calling widget group which parameters just like below
// Widget::$name($params1, $params2, $params3, ....);
Widget::sidebar(array('name'), array('My Tweets', '.....Latest Tweets'));

```
