<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class ViewDetailTest extends SuitePHPUnitFrameworkTestCase
{
    public function setUp()
    {
        parent::setUp();
        global $current_user;
        get_sugar_config_defaults();
        $current_user = BeanFactory::newBean('Users');
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testViewDetail()
    {
        // Execute the constructor and check for the Object type and type attribute
        $view = new ViewDetail();
        $this->assertInstanceOf('ViewDetail', $view);
        $this->assertInstanceOf('SugarView', $view);
        $this->assertAttributeEquals('detail', 'type', $view);
    }

    public function testpreDisplay()
    {
        //execute the method with required attributes preset, it will initialize the dv(detail view) attribute.
        $view = new ViewDetail();
        $view->module = 'Users';
        $view->bean = BeanFactory::newBean('Users');
        $view->ss = new Sugar_Smarty();
        $view->preDisplay();
        $this->assertInstanceOf('DetailView2', $view->dv);
        $this->asserttrue(is_array($view->dv->defs));

        //execute the method again for a different module with required attributes preset, it will initialize the dv(detail view) attribute.
        $view = new ViewDetail();
        $view->module = 'Meetings';
        $view->bean = BeanFactory::newBean('Meetings');
        $view->ss = new Sugar_Smarty();
        $view->preDisplay();
        $this->assertInstanceOf('DetailView2', $view->dv);
        $this->asserttrue(is_array($view->dv->defs));
    }

    public function testdisplay()
    {
        //execute the method with essential parameters set. it should return some html.
        $view = new ViewDetail();
        $view->module = 'Users';
        $view->bean = BeanFactory::newBean('Users');
        $view->bean->id = 1;
        $view->ss = new Sugar_Smarty();
        $view->preDisplay();

        ob_start();
        $view->display();
        $renderedContent = ob_get_contents();
        ob_end_clean();
        $this->assertGreaterThan(0, strlen($renderedContent));
    }
}
