<?php  namespace Widget;

use Mockery as m;

/**
 * Class WidgetTestCase
 */
class WidgetTestCase extends \PHPUnit_Framework_TestCase {

    protected $useDatabase = true;

    protected $artisan;

    public function setUp()
    {
        parent::setUp();

        //setup env
    }

    public function teardown()
    {
        m::close();
    }



}
