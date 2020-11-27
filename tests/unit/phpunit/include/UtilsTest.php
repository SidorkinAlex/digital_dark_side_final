<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

namespace SuiteCRM;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

include_once __DIR__ . '/../../../../include/utils.php';

class UtilsTest extends SuitePHPUnitFrameworkTestCase
{
    public function testGetAppString()
    {
        global $app_strings;
        
        // setup: test works only if it is not exists
        $this->assertTrue(!isset($app_strings['TEST_NONEXISTS_LABEL']));
        
        // test if label is not set
        
        $result = getAppString('TEST_NONEXISTS_LABEL');
        $this->assertEquals('TEST_NONEXISTS_LABEL', $result);
        
        // test if label is empty (bool:false)
        
        $app_strings['TEST_NONEXISTS_LABEL'] = '';
        
        $result = getAppString('TEST_NONEXISTS_LABEL');
        $this->assertEquals('TEST_NONEXISTS_LABEL', $result);
        
        // test if it founds
        
        $app_strings['TEST_NONEXISTS_LABEL'] = 'Hello test';
        
        $result = getAppString('TEST_NONEXISTS_LABEL');
        $this->assertEquals('Hello test', $result);
        

        unset($app_strings['TEST_NONEXISTS_LABEL']);
    }

    public function testencodeMultienumValue()
    {
        $this->assertEquals('', encodeMultienumValue(array()));
        $this->assertEquals('^foo^', encodeMultienumValue(array('foo')));
        $this->assertEquals('^foo^,^bar^', encodeMultienumValue(array('foo', 'bar')));
    }

    public function testunencodeMultienum()
    {
        $this->assertEquals(array('foo'), unencodeMultienum('^foo^'));
        $this->assertEquals(array('foo', 'bar'), unencodeMultienum('^foo^,^bar^'));
        // Will return the same array if given an array.
        $this->assertEquals(array('foo', 'bar'), unencodeMultienum(['foo', 'bar']));
    }

    public function testget_languages()
    {
        $this->assertEquals(['en_us' => 'English (US)'], get_languages());
        $this->assertEquals(['en_us' => 'English (US)'], get_all_languages());
        $this->assertEquals('English (US)', get_language_display('en_us'));
    }

    public function testget_current_language()
    {
        global $sugar_config;

        $_SESSION['authenticated_user_language'] = 'foo';
        $this->assertEquals('foo', get_current_language());
        $this->assertEquals('foo', get_current_language());

        $sugar_config['default_language'] = 'bar';
        $this->assertEquals('foo', get_current_language());
        unset($_SESSION['authenticated_user_language']);
        $this->assertEquals('bar', get_current_language());
    }

    public function testis_admin()
    {
        // Returns true if the user is an admin.
        $user = new \User();
        $user->is_admin = true;
        $this->assertTrue(is_admin($user));

        // Returns false if the user is not an admin.
        $user2 = new \User();
        $user2->is_admin = false;
        $this->assertFalse(is_admin($user2));

        // Returns false if no user object is passed.
        $this->assertFalse(is_admin(null));
    }
  
    public function testcheck_php_version()
    {
        // These are used because the tests would fail if the supported
        // versions changed, and the constants can't be redefined. So we
        // instead pass the min/recommended versions directly to the
        // function.
        $minimumVersion = '5.5.0';
        $recommendedVersion = '7.1.0';

        // Returns -1 when the version is less than the minimum version.
        $this->assertEquals(-1, check_php_version("5.4.0", $minimumVersion, $recommendedVersion));

        // Returns 0 when the version is above the minimum but below the recommended version.
        $this->assertEquals(0, check_php_version("7.0.0", $minimumVersion, $recommendedVersion));

        // Returns 1 when the version is at or above the recommended version.
        $this->assertEquals(1, check_php_version("7.1.0", $minimumVersion, $recommendedVersion));
        $this->assertEquals(1, check_php_version("7.2.0", $minimumVersion, $recommendedVersion));
        $this->assertEquals(1, check_php_version("8.0.0", $minimumVersion, $recommendedVersion));
        // Handles versions with a `-dev` suffix correctly.
        $this->assertEquals(1, check_php_version("7.4.0-dev", $minimumVersion, $recommendedVersion));
    }

    public function testreturn_bytes()
    {
        // Test bytes. If you input just '8', it'll output 8.
        $this->assertEquals(8, return_bytes('8'));

        // Test kibibytes.
        $this->assertEquals(8192, return_bytes('8K'));
        $this->assertEquals(8192, return_bytes('8k'));

        // Test mebibytes.
        // 8M is 8 mebibytes, 1 mebibyte is 1,048,576 bytes or 2^20 bytes.
        $this->assertEquals(8388608, return_bytes('8M'));
        $this->assertEquals(8388608, return_bytes('8m'));

        // Test gibibytes
        $this->assertEquals(8589934592, return_bytes('8G'));
        $this->assertEquals(8589934592, return_bytes('8g'));

        // Make sure it also understands strings with whitespace.
        $this->assertEquals(8192, return_bytes('  8K  '));
    }
}
