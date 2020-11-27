<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

class vCalTest extends SuitePHPUnitFrameworkTestCase
{
    public function setUp()
    {
        parent::setUp();

        global $current_user;
        get_sugar_config_defaults();
        $current_user = BeanFactory::newBean('Users');
    }

    public function testvCal()
    {
        // Execute the constructor and check for the Object type and  attributes
        $vcal = BeanFactory::newBean('vCals');

        $this->assertInstanceOf('vCal', $vcal);
        $this->assertInstanceOf('SugarBean', $vcal);

        $this->assertAttributeEquals('vcals', 'table_name', $vcal);
        $this->assertAttributeEquals('vCals', 'module_dir', $vcal);
        $this->assertAttributeEquals('vCal', 'object_name', $vcal);

        $this->assertAttributeEquals(true, 'new_schema', $vcal);
        $this->assertAttributeEquals(false, 'tracker_visibility', $vcal);
        $this->assertAttributeEquals(true, 'disable_row_level_security', $vcal);
    }

    public function testget_summary_text()
    {
        $vcal = BeanFactory::newBean('vCals');

        //test without setting name
        $this->assertEquals(null, $vcal->get_summary_text());

        //test with name set
        $vcal->name = 'test';
        $this->assertEquals('', $vcal->get_summary_text());
    }

    public function testfill_in_additional_list_fields()
    {
        $vcal = BeanFactory::newBean('vCals');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $vcal->fill_in_additional_list_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testfill_in_additional_detail_fields()
    {
        $vcal = BeanFactory::newBean('vCals');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $vcal->fill_in_additional_detail_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testget_list_view_data()
    {
        $vcal = BeanFactory::newBean('vCals');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $vcal->get_list_view_data();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }

        $this->markTestIncomplete('method has no implementation');
    }

    public function testget_freebusy_lines_cache()
    {
        self::markTestIncomplete('Asserting String Start Width is imposible if expected is empty srting');
        
        $vcal = BeanFactory::newBean('vCals');
        $user_bean = new User('1');

        $expectedStart = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VFREEBUSY\r\nORGANIZER;CN= :VFREEBUSY\r\n";
        $expectedEnd = "END:VFREEBUSY\r\nEND:VCALENDAR\r\n";

        $result = $vcal->get_freebusy_lines_cache($user_bean);

        $this->assertStringStartsWith($expectedStart, $result);
        $this->assertStringEndsWith($expectedEnd, $result);
    }

    public function testcreate_sugar_freebusy()
    {
        global $locale, $timedate;

        $vcal = BeanFactory::newBean('vCals');
        $user_bean = new User('1');

        $now_date_time = $timedate->getNow(true);
        $start_date_time = $now_date_time->get('yesterday');
        $end_date_time = $now_date_time->get('tomorrow');

        $result = $vcal->create_sugar_freebusy($user_bean, $start_date_time, $end_date_time);
        $this->assertGreaterThanOrEqual(0, strlen($result));
    }

    public function testget_vcal_freebusy()
    {
        $vcal = BeanFactory::newBean('vCals');
        $user_focus = new User('1');

        $expectedStart = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VFREEBUSY\r\nORGANIZER;CN= :VFREEBUSY\r\n";
        $expectedEnd = "END:VFREEBUSY\r\nEND:VCALENDAR\r\n";

        $result = $vcal->get_vcal_freebusy($user_focus);

        $this->assertStringStartsWith($expectedStart, $result);
        $this->assertStringEndsWith($expectedEnd, $result);
    }

    public function testcache_sugar_vcal()
    {
        $vcal = BeanFactory::newBean('vCals');
        $user_focus = new User('1');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $vcal->cache_sugar_vcal($user_focus);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testcache_sugar_vcal_freebusy()
    {
        $vcal = BeanFactory::newBean('vCals');
        $user_focus = new User('1');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $vcal->cache_sugar_vcal_freebusy($user_focus);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testfold_ical_lines()
    {
        //test with short strings
        $result = vCal::fold_ical_lines('testkey', 'testvalue');
        $this->assertEquals('testkey:testvalue', $result);

        //test with longer strings
        $expected = "testkey11111111111111111111111111111111111111111111111111111111111111111111\r\n	11111111111111111111111111111111:testvalue11111111111111111111111111111111\r\n	11111111111111111111111111111111111111111111111111111111111111111111";
        $result = vCal::fold_ical_lines('testkey'.str_repeat('1', 100), 'testvalue'.str_repeat('1', 100));
        $this->assertEquals($expected, $result);
    }

    public function testcreate_ical_array_from_string()
    {
        $iCalString = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VFREEBUSY\r\nORGANIZER;CN= :VFREEBUSY\r\nDTSTART:2016-01-09 00:00:00\r\nDTEND:2016-03-09 00:00:00\r\nDTSTAMP:2016-01-10 11:07:15\r\nEND:VFREEBUSY\r\nEND:VCALENDAR\r\n";
        $expected = array(
                        array('BEGIN', 'VCALENDAR'),
                        array('VERSION', '2.0'),
                        array('PRODID', '-//SugarCRM//SugarCRM Calendar//EN'),
                        array('BEGIN', 'VFREEBUSY'),
                        array('ORGANIZER;CN= ', 'VFREEBUSY'),
                        array('DTSTART', '2016-01-09 00:00:00'),
                        array('DTEND', '2016-03-09 00:00:00'),
                        array('DTSTAMP', '2016-01-10 11:07:15'),
                        array('END', 'VFREEBUSY'),
                        array('END', 'VCALENDAR'),
                    );
        $actual = vCal::create_ical_array_from_string($iCalString);
        $this->assertSame($expected, $actual);
    }

    public function testcreate_ical_string_from_array()
    {
        $expected = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VFREEBUSY\r\nORGANIZER;CN= :VFREEBUSY\r\nDTSTART:2016-01-09 00:00:00\r\nDTEND:2016-03-09 00:00:00\r\nDTSTAMP:2016-01-10 11:07:15\r\nEND:VFREEBUSY\r\nEND:VCALENDAR\r\n";
        $iCalArray = array(
                array('BEGIN', 'VCALENDAR'),
                array('VERSION', '2.0'),
                array('PRODID', '-//SugarCRM//SugarCRM Calendar//EN'),
                array('BEGIN', 'VFREEBUSY'),
                array('ORGANIZER;CN= ', 'VFREEBUSY'),
                array('DTSTART', '2016-01-09 00:00:00'),
                array('DTEND', '2016-03-09 00:00:00'),
                array('DTSTAMP', '2016-01-10 11:07:15'),
                array('END', 'VFREEBUSY'),
                array('END', 'VCALENDAR'),
        );
        $actual = vCal::create_ical_string_from_array($iCalArray);
        $this->assertSame($expected, $actual);
    }

    public function testescape_ical_chars()
    {
        $this->assertSame('', vCal::escape_ical_chars(''));
        $this->assertSame('\;\,', vCal::escape_ical_chars(';,'));
    }

    public function testunescape_ical_chars()
    {
        $this->assertSame('', vCal::unescape_ical_chars(''));
        $this->assertSame('; , \\', vCal::unescape_ical_chars('\\; \\, \\\\'));
    }

    public function testget_ical_event()
    {
        $user = new User(1);
        $meeting = BeanFactory::newBean('Meetings');

        $meeting->id = 1;
        $meeting->date_start = '2016-02-11 17:30:00';
        $meeting->date_end = '2016-02-11 17:30:00';
        $meeting->name = 'test';
        $meeting->location = 'test location';
        $meeting->description = 'test description';

        $expectedStart = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VEVENT\r\nUID:1\r\nORGANIZER;CN=:mailto:\r\nDTSTART:20160211T173000Z\r\nDTEND:20160211T173000Z\r\n";
        $expectedEnd = "\r\nSUMMARY:test\r\nLOCATION:test location\r\nDESCRIPTION:test description\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";

        $result = vCal::get_ical_event($meeting, $user);

        $this->assertStringStartsWith($expectedStart, $result);
        $this->assertStringEndsWith($expectedEnd, $result);
    }
}
