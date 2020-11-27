<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class ReleaseTest extends SuitePHPUnitFrameworkTestCase
{
    public function testRelease()
    {
        // Execute the constructor and check for the Object type and  attributes
        $release = BeanFactory::newBean('Releases');

        $this->assertInstanceOf('Release', $release);
        $this->assertInstanceOf('SugarBean', $release);

        $this->assertAttributeEquals('releases', 'table_name', $release);
        $this->assertAttributeEquals('Releases', 'module_dir', $release);
        $this->assertAttributeEquals('Release', 'object_name', $release);

        $this->assertAttributeEquals(true, 'new_schema', $release);
    }

    public function testget_summary_text()
    {
        $release = BeanFactory::newBean('Releases');

        //test without setting name
        $this->assertEquals(null, $release->get_summary_text());

        //test with name set
        $release->name = 'test';
        $this->assertEquals('test', $release->get_summary_text());
    }

    public function testget_releases()
    {
        $release = BeanFactory::newBean('Releases');

        //test with default params
        $result = $release->get_releases();
        $this->assertTrue(is_array($result));

        //test with custom params
        $result = $release->get_releases(true, 'Hidden', 'name is not null');
        $this->assertTrue(is_array($result));
    }

    public function testfill_in_additional_list_fields()
    {
        $release = BeanFactory::newBean('Releases');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $release->fill_in_additional_list_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testfill_in_additional_detail_fields()
    {
        $release = BeanFactory::newBean('Releases');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $release->fill_in_additional_detail_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testget_list_view_data()
    {
        $release = BeanFactory::newBean('Releases');

        $release->name = 'test';
        $release->status = 'Hidden';

        $expected = array(
                    'NAME' => 'test',
                    'STATUS' => 'Hidden',
                    'ENCODED_NAME' => 'test',
                    'ENCODED_STATUS' => null,
        );

        $actual = $release->get_list_view_data();

        $this->assertSame($expected, $actual);
    }

    public function testbuild_generic_where_clause()
    {
        $release = BeanFactory::newBean('Releases');

        //test with empty string params
        $expected = "name like '%'";
        $actual = $release->build_generic_where_clause('');
        $this->assertSame($expected, $actual);

        //test with valid string params
        $expected = "name like 'test%'";
        $actual = $release->build_generic_where_clause('test');
        $this->assertSame($expected, $actual);
    }
}
