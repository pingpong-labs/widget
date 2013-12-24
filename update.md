Coming Soon
------------
This package still under development. What next ?

Register :
```php
// Grouping Widget
//Widget::group($group_name, Closure $callback);

Widget::group('sidebar', function(){
    
    // set the widget for sidebar
    // Widget::set($name, $order, Closure $callback);
    
    Widget::set('calendar', 0,  function(){
      return 'This is calendar widget!';
    });
    
    Widget::set('latestPost', 1,  function(){
      return 'This is latest post widget!';
    });
    
});

// Eloquent Widget
Widget::model('Post:all')
WIdget::model('Post:latest', $fields = array());
```

Calling widget:
```php
Widget::sidebar();
```
  
