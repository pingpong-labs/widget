<?php namespace Widget;

use Pingpong\Widget\Widget as Widget;

class Foo {
    function run($echo = 'bar'){
        return $echo;
    }
    function handle($echo = 'baz'){
        return $echo;
    }
}

class WidgetTest extends WidgetTestCase
{

    protected $app;

    public function setUp()
    {

        parent::setUp();

        $this->widget = new Widget;

    }

    public function testClosures()
    {

        $this->widget->register(
                'foo',
                function ($echo = 'bar') {

                    return $echo;

                }
        );

        $this->assertEquals('bar', $this->widget->foo());
        $this->assertEquals('baz', $this->widget->foo('baz'));
    }

    public function testGroups()
    {

        $this->widget->register(
                'foo',
                function ($echo = 'bar') {

                    return $echo;

                }
        );

        $this->widget->register(
                'boo',
                function ($echo = 'bar') {

                    return $echo;

                }
        );

        $this->widget->group('sidebar', array('foo', 'boo'));

        ob_start();
        $this->widget->sidebar();
        $result = ob_get_clean();

        $this->assertEquals('barbar', $result);

        ob_start();
        $this->widget->sidebar(array('baz'),array('baz'));
        $result = ob_get_clean();

        $this->assertEquals('bazbaz', $result);

    }

    public function testClasses()
    {

        $this->widget->register('foo','Widget\Foo@run');
        $this->widget->register('boo','Widget\Foo');

        $this->assertEquals('bar', $this->widget->foo());
        $this->assertEquals('baz', $this->widget->boo('baz'));
    }

}