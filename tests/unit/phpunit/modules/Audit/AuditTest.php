<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

require_once 'modules/Audit/Audit.php';
class AuditTest extends SuitePHPUnitFrameworkTestCase
{
    public function setUp()
    {
        parent::setUp();

        global $current_user;
        get_sugar_config_defaults();
        $current_user = BeanFactory::newBean('Users');
    }

    public function testAudit()
    {
        // Execute the constructor and check for the Object type and  attributes
        $audit = BeanFactory::newBean('Audit');
        $this->assertInstanceOf('Audit', $audit);
        $this->assertInstanceOf('SugarBean', $audit);
        $this->assertAttributeEquals('Audit', 'module_dir', $audit);
        $this->assertAttributeEquals('Audit', 'object_name', $audit);
    }

    public function testget_summary_text()
    {
        $audit = BeanFactory::newBean('Audit');

        //test without setting name
        $this->assertEquals(null, $audit->get_summary_text());

        //test with name set
        $audit->name = 'test';
        $this->assertEquals('test', $audit->get_summary_text());
    }

    public function testcreate_export_query()
    {
        $audit = BeanFactory::newBean('Audit');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->create_export_query('', '');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testfill_in_additional_list_fields()
    {
        $audit = BeanFactory::newBean('Audit');
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->fill_in_additional_list_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testfill_in_additional_detail_fields()
    {
        $audit = BeanFactory::newBean('Audit');
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->fill_in_additional_detail_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testfill_in_additional_parent_fields()
    {
        $audit = BeanFactory::newBean('Audit');
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->fill_in_additional_parent_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testget_list_view_data()
    {
        $audit = BeanFactory::newBean('Audit');
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->get_list_view_data();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testget_audit_link()
    {
        $audit = BeanFactory::newBean('Audit');
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $audit->get_audit_link();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testget_audit_list()
    {
        global $focus;
        $focus = BeanFactory::newBean('Accounts'); //use audit enabbled module object

        $audit = BeanFactory::newBean('Audit');

        //execute the method and verify that it returns an array
        $result = $audit->get_audit_list();
        $this->assertTrue(is_array($result));
    }

    public function testgetAssociatedFieldName()
    {
        global $focus;
        $focus = BeanFactory::newBean('Accounts'); //use audit enabbled module object

        $audit = BeanFactory::newBean('Audit');

        //test with name field
        $result = $audit->getAssociatedFieldName('name', '1');
        $this->assertEquals('1', $result);

        //test with parent_id field
        $result = $audit->getAssociatedFieldName('parent_id', '1');
        $this->assertEquals(null, $result);
    }
}
