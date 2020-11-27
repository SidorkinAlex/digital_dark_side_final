<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

require_once 'include/utils/progress_bar_utils.php';
class progress_bar_utilsTest extends SuitePHPUnitFrameworkTestCase
{
    public function testprogress_bar_flush()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            progress_bar_flush(false);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testdisplay_flow_bar()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            ob_start();
            display_flow_bar('test', 0, 200, false);
            ob_end_clean();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function teststart_flow_bar()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            ob_start();
            start_flow_bar('test', 1, false);
            ob_end_clean();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testdestroy_flow_bar()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            ob_start();
            destroy_flow_bar('test', false);
            ob_end_clean();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testdisplay_progress_bar()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            ob_start();
            display_progress_bar('test', 80, 100, false);
            ob_end_clean();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }

    public function testupdate_progress_bar()
    {
        //execute the method and test if it doesn't throw an exception.
        //this method uses flush so we cannot get and verify content printed
        try {
            ob_start();
            update_progress_bar('test', 80, 100, false);
            ob_end_clean();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
        }
    }
}
