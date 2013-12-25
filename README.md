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
------------------

  First, you must register your widget on `app/widget.php` file.
  
  Widget without parameters.
  ---------------------------
  
  ```php
  //  file : app/widget.php
  //  registering new widget
  
  Widget::register('newsfeed', function(){
    return View::make('awesome');
  });
  
  // calling Eloquent on widget
  Widget::register('headline', function(){
    $posts = Post::all();
    return View::make('headine', compact('posts'));
  });
  
  ```
  Widget with parameters.
  -------------
  If you want to registering widget with some parameter, you can register it like below:
  
  ```php
  
  // just add ':' on the name of widget
  // the parameter must be have a default value
  
  Widget::register(':boxSidebar', function($title = null, $desc = null){
    $html = '<h3>'.$title.'</h3>';
    $html.= '<div>'.$desc.'</div>';
    return $html;
  });
  
  // Example with eloquent
  Widget::register(':getPost', function($id = null){
    $posts = Post::find($id);
    return View::make('widgets.singlePost', compact('posts'));
  });
  
  ```
  
  Calling widget :
  -------------------
  Globally, on controllers or model/eloquent or view,  you can only call widget like below:
  
  ```php
  
  // widget without parameter
  // type 1
  
  Widget::newsfeed()
  
  // type 2
  Widget::get('newsfeed')
  
  // type 3 - widget with parametre
  Widget::get('boxSidebar/This is Title/This is Description') // name/parameter/parameter/parameter
  Widget::get('getPost/1')
  
  // type 4 - widget with parameters
  Widget::boxSidebar('Sidebar','This is sidebar!')
  Widget::getPost(1)
  ```
  
  ON VIEW:
  Specially with view, when you register a widget without parameter. You can only call that like below:
  ----------------------------
  
  ```php
  
  // type 1
  {{ Widget::newsfeed() }}
  
  // type 2
  {{ Widget::get('newsfeed') }}
  
  
  {{ Widget::get('boxSidebar/This is Title/This is Description') }} 
  {{ Widget::get('getPost/1') }}
  
  // type 3 - with parameters
  {{ Widget::boxSidebar('Sidebar','This is sidebar!') }}
  {{ Widget::getPost(1) }}

  // type 3
  @newsfeed
  @headline
  
  ```
  If you registering widget with parameters. You can only call that like below:
  ----------------------------
  Format : `[name:parameter/parameter/parameter/]` // note: parameter is not limited ...
  
  ```php
  // on blade view

  // calling box Sidebar above
  [boxSidebar:Calendar/This is a calendar widget]
  
  // calling getPost above
  [getPost:1]
  ```

Testing Widget
------------
On this widget i put `routes.php` file on your Laravel App.This is use for validate or testing your widget before use them. To use, can call route like below:
  --------------
  For example i use` http://localhost:8000/`. Now, open the url :

  For showing all registered widgets
  ```
  http://localhost:8000/widget/all
  ```
  
  For showing one widget
  ```
  http://localhost:8000/widget/test/{widgetName}
  ```
  Eg:
  ```
  http://localhost:8000/widget/test/newsfeed
  ```
  
  For showing one widget with parameter:
  ```
  http://localhost:8000/widget/test/{widgetName}/?params={parameter}
  ```
  Eg:
  ```
  http://localhost:8000/widget/test/newsfeed?params=1/2/3/
  ```
  
  - Note: separate the parameter with slash
  



Developer
--------
[Gravitano](https://github.com/gravitano)

