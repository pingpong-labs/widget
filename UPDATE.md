#Registering Your Widget
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

```

#Calling Widget 
---------------------------------

```php

Widget::awesome();

Widget::hello('Jhon');

Widget::box('Latest News', 'This is a description of latest news');

```
