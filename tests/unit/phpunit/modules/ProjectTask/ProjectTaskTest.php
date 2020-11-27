<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class ProjectTaskTest extends SuitePHPUnitFrameworkTestCase
{
    public function setUp()
    {
        parent::setUp();

        global $current_user;
        get_sugar_config_defaults();
        $current_user = BeanFactory::newBean('Users');
    }
    
    public function testcreate_export_query()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test with empty string params
        $expected = "SELECT
				project_task.*,
                users.user_name as assigned_user_name  FROM project_task LEFT JOIN project ON project_task.project_id=project.id AND project.deleted=0  LEFT JOIN users
                   	ON project_task.assigned_user_id=users.id where  project_task.deleted=0 ";
        $actual = $projectTask->create_export_query('', '');
        $this->assertSame($expected, $actual);

        //test with valid string params
        $expected = "SELECT
				project_task.*,
                users.user_name as assigned_user_name  FROM project_task LEFT JOIN project ON project_task.project_id=project.id AND project.deleted=0  LEFT JOIN users
                   	ON project_task.assigned_user_id=users.id where (users.user_name= \"\") AND  project_task.deleted=0  ORDER BY project_task.id";
        $actual = $projectTask->create_export_query('project_task.id', 'users.user_name= ""');
        $this->assertSame($expected, $actual);
    }

    public function testProjectTask()
    {
        // Execute the constructor and check for the Object type and  attributes
        $projectTask = BeanFactory::newBean('ProjectTask');

        $this->assertInstanceOf('ProjectTask', $projectTask);
        $this->assertInstanceOf('SugarBean', $projectTask);

        $this->assertAttributeEquals('project_task', 'table_name', $projectTask);
        $this->assertAttributeEquals('ProjectTask', 'module_dir', $projectTask);
        $this->assertAttributeEquals('ProjectTask', 'object_name', $projectTask);

        $this->assertAttributeEquals(true, 'new_schema', $projectTask);
        $this->assertAttributeEquals(false, '_skipParentUpdate', $projectTask);

        $this->assertAttributeEquals(100, 'utilization', $projectTask);
    }

    public function testskipParentUpdate()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test with default parameter value
        $projectTask->skipParentUpdate();
        $this->assertAttributeEquals(true, '_skipParentUpdate', $projectTask);

        //test with parameter value  = true
        $projectTask->skipParentUpdate(false);
        $this->assertAttributeEquals(false, '_skipParentUpdate', $projectTask);
    }

    public function testsave()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $projectTask->name = 'test';
        //$projectTask->project_id = "1";
        $projectTask->assigned_user_id = '1';
        $projectTask->description = 'test description';
        $projectTask->parent_task_id = 1;

        $projectTask->save();

        //test for record ID to verify that record is saved
        $this->assertTrue(isset($projectTask->id));
        $this->assertEquals(36, strlen($projectTask->id));

        //test _get_depends_on_name method
        $this->_get_depends_on_name($projectTask->id);

        //mark the record as deleted and verify that this record cannot be retrieved anymore.
        $projectTask->mark_deleted($projectTask->id);
        $result = $projectTask->retrieve($projectTask->id);
        $this->assertEquals(null, $result);
    }

    public function _get_depends_on_name($id)
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $result = $projectTask->_get_depends_on_name($id);

        $this->assertEquals('1', $projectTask->depends_on_name_owner);
        $this->assertEquals('ProjectTask', $projectTask->depends_on_name_mod);
        $this->assertEquals('test', $result);
    }

    public function testupdateParentProjectTaskPercentage()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $projectTask->updateParentProjectTaskPercentage();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testgetProjectTaskParent()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $projectTask->parent_task_id = 1;
        $result = $projectTask->getProjectTaskParent();
        $this->assertEquals(false, $result);
    }

    public function testgetAllSubProjectTasks()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $result = $projectTask->getAllSubProjectTasks();
        $this->assertTrue(is_array($result));
    }

    public function testupdateStatistic()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $projectTask->updateStatistic();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testfill_in_additional_detail_fields()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test without setting assigned_user_id
        $projectTask->fill_in_additional_detail_fields();
        $this->assertEquals('', $projectTask->assigned_user_name);

        //test with assigned_user_id set
        $projectTask->assigned_user_id = 1;
        $projectTask->fill_in_additional_detail_fields();
        $this->assertEquals('Administrator', $projectTask->assigned_user_name);
    }

    public function testfill_in_additional_list_fields()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test without setting assigned_user_id
        $projectTask->fill_in_additional_list_fields();
        $this->assertEquals('', $projectTask->assigned_user_name);

        //test with assigned_user_id set
        $projectTask->assigned_user_id = 1;
        $projectTask->fill_in_additional_list_fields();
        $this->assertEquals('Administrator', $projectTask->assigned_user_name);
    }

    public function testget_summary_text()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test without setting name
        $this->assertEquals(null, $projectTask->get_summary_text());

        //test with name set
        $projectTask->name = 'test';
        $this->assertEquals('test', $projectTask->get_summary_text());
    }

    public function test_get_project_name()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test with a empty string
        $result = $projectTask->_get_project_name('');
        $this->assertEquals('', $result);

        //test with a non empty invalid id
        $result = $projectTask->_get_project_name('1');
        $this->assertEquals('', $result);
    }

    public function test_get_parent_name()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test with a empty string
        $result = $projectTask->_get_parent_name('');
        $this->assertEquals('', $result);

        //test with a non empty invalid id
        $result = $projectTask->_get_parent_name('1');
        $this->assertEquals('', $result);
    }

    public function testbuild_generic_where_clause()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        //test with empty string params
        $expected = "project_task.name like '%'";
        $actual = $projectTask->build_generic_where_clause('');
        $this->assertSame($expected, $actual);

        //test with valid string params
        $expected = "project_task.name like 'test%'";
        $actual = $projectTask->build_generic_where_clause('test');
        $this->assertSame($expected, $actual);
    }

    public function testget_list_view_data()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $projectTask->name = 'tes user';
        $projectTask->description = 'test assigned user';
        $projectTask->parent_type = 'Project';

        $expected = array(
                'NAME' => 'tes user',
                'DESCRIPTION' => 'test assigned user',
                'ORDER_NUMBER' => '1',
                'DELETED' => 0,
                'UTILIZATION' => 100,
                'PARENT_MODULE' => 'Project',
                'FIRST_NAME' => '',
                'LAST_NAME' => '',
                'CONTACT_NAME' => ' ',
                'TITLE' => ':  ',
        );

        $actual = $projectTask->get_list_view_data();

        $this->assertSame($expected, $actual);
    }

    public function testbean_implements()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $this->assertEquals(false, $projectTask->bean_implements('')); //test with blank value
        $this->assertEquals(false, $projectTask->bean_implements('test')); //test with invalid value
        $this->assertEquals(true, $projectTask->bean_implements('ACL')); //test with valid value
    }

    public function testlistviewACLHelper()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $expected = array('MAIN' => 'a', 'PARENT' => 'a', 'PARENT_TASK' => 'a');
        $actual = $projectTask->listviewACLHelper();
        $this->assertSame($expected, $actual);
    }

    public function testgetUtilizationDropdown()
    {
        $projectTask = BeanFactory::newBean('ProjectTask');

        $expected = "<select name=\"utilization\">\n<OPTION value='0'>none</OPTION>\n<OPTION value='25'>25</OPTION>\n<OPTION value='50'>50</OPTION>\n<OPTION value='75'>75</OPTION>\n<OPTION value='100'>100</OPTION></select>";
        $actual = getUtilizationDropdown($projectTask, 'utilization', '0', 'EditView');
        $this->assertSame($expected, $actual);
    }
}
