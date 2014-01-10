#Registering your widget
----------------------------------

```php

//general
Widget::register('awesome', function(){

	return View::make('awesome');

});

// widget with parameters
Widget::register('hello', function($name){

	return "Hello, $name !";

});

Widget::register('box', function($title, $description){

	return View::make('widgets.box', compact('title', 'description'));

});

// one again a simple example

Widget::register('categories', function(){
	return View::make('widgets.categories');
});

Widget::register('latestPost', function(){
	return View::make('widgets.latestPost');
});

// Grouping widget
// Widget::group($name, Array $widgets);
Widget::group('sidebar', array('categories', 'latestPost'));

// Grouping widget have parameters
Widget::group('footer', array('hello', 'box'));



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

// calling widget group have parameters just like below
// Widget::$name($params1, $params2, $params3, ....);
Widget::sidebar(array('name'), array('My Tweets', '.....Latest Tweets'));

```
