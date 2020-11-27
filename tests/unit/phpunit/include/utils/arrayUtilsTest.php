<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

require_once 'include/utils/array_utils.php';
class array_utilsTest extends SuitePHPUnitFrameworkTestCase
{
    public function testvar_export_helper()
    {
        //execute the method and test if it returns expected values
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2');

        $expected = "array (\n  'Key1' => 'value1',\n  'Key2' => 'value2',\n)";
        $actual = var_export_helper($tempArray);
        $this->assertSame($expected, $actual);
    }

    public function testoverride_value_to_string()
    {
        //execute the method and test if it returns expected values
        $expected = "\$array_name['value_name'] = 'value';";
        $actual = override_value_to_string('array_name', 'value_name', 'value');
        $this->assertSame($expected, $actual);
    }

    public function testadd_blank_option()
    {
        //execute the method with array not having any blank key value pair. function will return an array with blank key value pair added.
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2');
        $expected = array('' => '', 'Key1' => 'value1', 'Key2' => 'value2');

        $actual = add_blank_option($tempArray);
        $this->assertSame($expected, $actual);

        //execute the method with array having a blank key value pair. function will return the same array back without any change.
        $tempArray = array('' => '', 'Key1' => 'value1', 'Key2' => 'value2');
        $expected = array('' => '', 'Key1' => 'value1', 'Key2' => 'value2');

        $actual = add_blank_option($tempArray);
        $this->assertSame($expected, $actual);
    }

    public function testoverride_value_to_string_recursive()
    {
        //execute the method and test if it returns expected values

        //without keys
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2');
        $expected = "\$tempArray=array (\n  'Key1' => 'value1',\n  'Key2' => 'value2',\n);";
        $actual = override_value_to_string_recursive('', 'tempArray', $tempArray);
        $this->assertSame($expected, $actual);

        //with keys
        $tempArray = array();
        $tempArray['Key1']['Key2'] = array('Key3' => 'value', 'Key4' => 'value');
        $expected = "\$tempArray['key1']['key2']=array (\n  'Key1' => \n  array (\n    'Key2' => \n    array (\n      'Key3' => 'value',\n      'Key4' => 'value',\n    ),\n  ),\n);";
        $actual = override_value_to_string_recursive(array('key1', 'key2'), 'tempArray', $tempArray);

        $this->assertSame($expected, $actual);
    }

    public function testoverride_recursive_helper()
    {
        //execute the method and test if it returns expected values

        //without keys
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2');
        $expected = "=array (\n  'Key1' => 'value1',\n  'Key2' => 'value2',\n);";
        $actual = override_recursive_helper('', 'tempArray', $tempArray);
        $this->assertSame($expected, $actual);

        //with keys
        $tempArray = array();
        $tempArray['Key1']['Key2'] = array('Key3' => 'value', 'Key4' => 'value');
        $expected = "['key1']['key2']=array (\n  'Key1' => \n  array (\n    'Key2' => \n    array (\n      'Key3' => 'value',\n      'Key4' => 'value',\n    ),\n  ),\n);";
        $actual = override_recursive_helper(array('key1', 'key2'), 'tempArray', $tempArray);
        $this->assertSame($expected, $actual);
    }

    public function testoverride_value_to_string_recursive2()
    {
        //execute the method and test if it returns expected values

        //null array
        $expected = null;
        $actual = override_value_to_string_recursive2('tempArray', 'key1', '', false);
        $this->assertSame($expected, $actual);

        //simple array
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2');
        $expected = "\$['tempArray']['Key1'] = 'value1';\n$['tempArray']['Key2'] = 'value2';\n";
        $actual = override_value_to_string_recursive2('', 'tempArray', $tempArray);
        $this->assertSame($expected, $actual);

        //complex array
        $tempArray = array();
        $tempArray['Key1']['Key2'] = array('Key3' => 'value', 'Key4' => 'value');
        $expected = "\$tempArray['key1']['Key2']['Key3'] = 'value';\n\$tempArray['key1']['Key2']['Key4'] = 'value';\n";
        $actual = override_value_to_string_recursive2('tempArray', 'key1', $tempArray['Key1']);
        $this->assertSame($expected, $actual);
    }

    public function testobject_to_array_recursive()
    {
        //execute the method and test if it returns expected values

        //test invalid input
        $obj = '';
        $expected = '';
        $actual = object_to_array_recursive($obj);
        $this->assertSame($expected, $actual);

        //test with a valid object
        $obj = new TimeDate();
        $expected = array('dbDayFormat' => 'Y-m-d', 'dbTimeFormat' => 'H:i:s', 'allow_cache' => true);
        $actual = object_to_array_recursive($obj);

        $this->assertSame($expected, $actual);
    }

    public function testdeepArrayDiff()
    {
        //execute the method and test if it returns expected values

        //same simple arrays
        $tempArray1 = array('Key1' => 'value1', 'Key2' => 'value2');
        $tempArray2 = array('Key1' => 'value1', 'Key2' => 'value2');
        $expected = array();
        $actual = deepArrayDiff($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);

        //different simple arrays
        $tempArray1 = array('Key1' => 'value1', 'Key2' => 'value2');
        $tempArray2 = array('Key1' => 'value1', 'Key2' => 'value3');
        $expected = array('Key2' => 'value2');
        $actual = deepArrayDiff($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);

        //same complex arrays
        $tempArray1 = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $tempArray2 = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $expected = array();
        $actual = deepArrayDiff($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);

        //complex arrays with different root node
        $tempArray1 = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $tempArray2 = array('Key2' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $expected = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $actual = deepArrayDiff($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);

        //complex arrays with different child node
        $tempArray1 = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $tempArray2 = array('Key1' => array('Key2' => 'value2', 'Key4' => 'value4'));
        $expected = array('Key1' => array('Key3' => 'value3'));
        $actual = deepArrayDiff($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);
    }

    public function testsetDeepArrayValue()
    {
        //execute the method and test if it returns expected values

        //add to existing array
        $tempArray = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'));
        $expected = array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'), 'key4' => 'value4');
        setDeepArrayValue($tempArray, 'key4', 'value4');
        $this->assertSame($tempArray, $expected);

        //add to empty array
        $tempArray = array();
        $expected = array('key1' => array('key2' => array('key3' => 'value3')));
        setDeepArrayValue($tempArray, 'key1_key2_key3', 'value3');
        //var_dump($tempArray);
        $this->assertSame($tempArray, $expected);
    }

    public function testarray_merge_values()
    {
        //execute the method and test if it returns expected values

        //try with two different length arrays
        $tempArray1 = array('v1', 'v2', 'v3');
        $tempArray2 = array('v4', 'v5');
        $actual = array_merge_values($tempArray1, $tempArray2);
        $this->assertFalse($actual);

        //try with same length arrays.
        $tempArray1 = array('v1', 'v2', 'v3');
        $tempArray2 = array('v4', 'v5', 'v6');
        $expected = array('v1v4', 'v2v5', 'v3v6');
        $actual = array_merge_values($tempArray1, $tempArray2);
        $this->assertSame($expected, $actual);
    }

    public function testarray_search_insensitive()
    {
        //execute the method and test if it returns expected value

        //test with invalid input
        $tempArray = '';
        $actual = array_search_insensitive('', $tempArray);
        $this->assertFalse($actual);

        //test with invalid needle..
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2', 'Key3' => 'value3', 'key4' => 'value4');
        $actual = array_search_insensitive('', $tempArray);
        $this->assertFalse($actual);

        //test with valid needle and haystack.
        $tempArray = array('Key1' => 'value1', 'Key2' => 'value2', 'Key3' => 'value3', 'key4' => 'value4');
        $actual = array_search_insensitive('value4', $tempArray);
        $this->assertTrue($actual);
    }

    public function testget()
    {
        //execute the method and test if it returns expected values

        //test for a top level key
        $tempArray = new SugarArray(array('Key1' => array('Key2' => 'value2', 'Key3' => 'value3'), 'key4' => 'value4'));
        $expected = 'value4';
        $actual = $tempArray->get('key4');
        $this->assertSame($expected, $actual);

        //test for a child level key with dot notation
        $tempArray = new SugarArray(array('key1' => array('key2' => 'value2', 'key3' => 'value3'), 'key4' => 'value4'));
        $expected = 'value3';
        $actual = $tempArray->get('key1.key3');
        $this->assertSame($expected, $actual);
    }

    public function teststaticGet()
    {
        //execute the method and test if it returns expected values

        //test for a top level key
        $haystack = array('key1' => array('key2' => 'value2', 'key3' => 'value3'), 'key4' => 'value4');
        $expected = 'value4';
        $actual = SugarArray::staticGet($haystack, 'key4');
        $this->assertSame($expected, $actual);

        //test for a child level key with dot notation
        $haystack = array('key1' => array('key2' => 'value2', 'key3' => 'value3'), 'key4' => 'value4');
        $expected = 'value3';
        $actual = SugarArray::staticGet($haystack, 'key1.key3');
        $this->assertSame($expected, $actual);
    }

    /**
     * This function tests fixIndexArrayFormat() function.
     * The idea is that both arrays represents the same index definition, one defined in vardefs.php
     * and the other obtained with get_indices() function.
     * After applying fixIndexArrayFormat() to both arrays we compare it as compareVarDefs() function does:
     *
     */
    public function testfixIndexArrayFormat()
    {
        $index1 = [
            'user_name',
            'is_group',
            'status',
            'last_name (30)',
            'first_name (30)',
            'id'
        ];

        $index2 = [
            'user_name',
            'is_group',
            'status',
            'last_name    (  30 ) ',
            'first_name  ( 30  ) ',
            'id'
        ];

        $index1 = fixIndexArrayFormat($index1);
        $index2 = fixIndexArrayFormat($index2);
        $this->assertTrue(array_map('strtolower', $index1) == array_map('strtolower', $index2));

        $index3 = [
            'user_name',
            'is_group',
            'status',
            'last_name (30)',
            'first_name (30)',
            'id'
        ];

        $index4 = [
            'user_name',
            'is_group',
            'status',
            'last_name    (  30 )',
            'first_name  ( 50  )',
            'id'
        ];

        $index3 = fixIndexArrayFormat($index3);
        $index4 = fixIndexArrayFormat($index4);
        $this->assertFalse(array_map('strtolower', $index3) == array_map('strtolower', $index4));
    }
}
