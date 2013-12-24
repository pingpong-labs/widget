Widget System - Laravel 4.*
======

Simple widget system for create awesome feature on blade templating Laravel 4.*

Installation
------------

1. Open your `composer.json` file and add new require `"pingpong/widget": "dev-master"`
2. Next, open your terminal and run `composer update`
3. After composer updated, create new file named `widget.php` on `[laravel-folder]/app/` folder.
4. Next, add new service provider on service provider array :
  
    ```php
    'providers' => array(
      //....

      'Pingpong\Widget\WidgetServiceProvider'
    )
    ```
    
5. Finish

Example
-------
First, register your widget on `app/widget.php` file.

  ```php
  //  file : app/widget.php
  //  registering new widget
  //
  
  Widget::register('newsfeed', function(){
    return View::make('awesome');
  });
  
  // calling Eloquent on widget
  Widget::register('headline', function(){
    $posts = Post::all();
    return View::make('headine', compact('posts'));
  });
  
  
  // calling Controller on widget
  Widget::register('widgetController', function(){
    $controller = new SiteController();
    $homepage = $controller->index();
    return View::make('sites.index', compact('homepage'));
  });
  ```
  
Before use the widget, you can see all registered widget using this code.

```php
  Widget::all() // return array of all widgets
  
  // or you can 
  Widget::all(TRUE); // for print all widgets 
```

if you call `Widget::all(TRUE)` , you can see the result like this:

```html
Array(
  [timeline] => Array
        (
            [name] => timeline
            [action] => Closure Object
                (
                    [this] => Pingpong\Widget\WidgetServiceProvider Object
                    (
                    ......
                    ........
                    )
                )
        )
)
```
  
  
Call just write :
  
  ```php
  // type 1
  Widget::newsfeed()
  
  // type 2
  Widget::get('newsfeed')
  ```
  
On view
  
  ```php
  // type 1
  {{ Widget::newsfeed() }}
  // type 2
  {{ Widget::get('newsfeed') }}
  ```

Developer
--------
[Gravitano](https://github.com/gravitano)
[See next update]
