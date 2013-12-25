<?php

/**
 *------------------------------------------
 * Show all widgets
 *------------------------------------------
 * GET /widget/all
 *
 */
Route::get('widget/all', function(){
	Widget::all(TRUE);	
});

/**
 *------------------------------------------
 * Testing a widget
 *------------------------------------------
 * GET /widget/{name}
 *
 * eg : /widget/newsfeed
 */
Route::get('widget/{name}', function($name){
	$params = Input::has('params') ? Input::get('params') : null;
	return Widget::get($name.'/'.$params);
});