<?php

use SuiteCRM\Test\SuitePHPUnitFrameworkTestCase;

include_once __DIR__ . '/../../../../../include/Imap/ImapHandlerFakeData.php';
include_once __DIR__ . '/../../../../../include/Imap/ImapHandlerFake.php';
require_once __DIR__ . '/../../../../../modules/InboundEmail/InboundEmail.php';

/**
 * Like tempfile() but takes a mode
 *
 * @param $mode
 * @return bool|resource
 */
function tempFileWithMode($mode)
{
    $path = tempnam(sys_get_temp_dir(), '');
    $file = fopen($path, $mode);
    register_shutdown_function(function() use($path) {
        if (file_exists($path)) {
            unlink($path);
        }
    });
    return $file;
}

class InboundEmailTest extends SuitePHPUnitFrameworkTestCase
{
    // ---------------------------------------------
    // ----- FOLLOWIN TESTS ARE USING FAKE IMAP ----
    // ------------------------------------------------->


    public function testConnectMailServerFolderInboundForceFirstMailbox()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('close', null, [null]);
        $fake->add('ping', null, [true]);
        $fake->add('reopen', ['{:/service=}first', 32768, 0], [true]);
        $imap = new ImapHandlerFake($fake);
        $ie = new InboundEmail($imap);
        $_REQUEST['folder'] = 'inbound';
        $_REQUEST['folder_name'] = [];
        $ie->mailboxarray = ['first'];
        $ret = $ie->connectMailserver(false, true);
        $this->assertEquals('true', $ret);
    }

    public function testConnectMailServerFolderInboundForceTestFolder()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('close', null, [null]);
        $fake->add('ping', null, [true]);
        $fake->add('reopen', ['{:/service=}test', 32768, 0], [true]);
        $imap = new ImapHandlerFake($fake);
        $ie = new InboundEmail($imap);
        $_REQUEST['folder'] = 'inbound';
        $_REQUEST['folder_name'] = 'test';
        $ret = $ie->connectMailserver(false, true);
        $this->assertEquals('true', $ret);

    }

    public function testConnectMailServerFolderInboundForce()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('close', null, [null]);
        $fake->add('ping', null, [true]);
        $fake->add('reopen', ['{:/service=}INBOX', 32768, 0], [true]);
        $imap = new ImapHandlerFake($fake);
        $ie = new InboundEmail($imap);
        $_REQUEST['folder'] = 'inbound';
        $ret = $ie->connectMailserver(false, true);
        $this->assertEquals('true', $ret);
    }

    public function testConnectMailServerFolderSentForce()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('close', null, [null]);
        $fake->add('ping', null, [true]);
        $fake->add('reopen', ['{:/service=}', 32768, 0], [true]);
        $imap = new ImapHandlerFake($fake);
        $ie = new InboundEmail($imap);
        $_REQUEST['folder'] = 'sent';
        $ret = $ie->connectMailserver(false, true);
        $this->assertEquals('true', $ret);
    }

    public function testConnectMailserverNoGood()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('setTimeout', [1, 15], [true]);
        $fake->add('setTimeout', [2, 15], [true]);
        $fake->add('setTimeout', [3, 15], [true]);
        $fake->add('open', ["{:/service=/ssl/tls/validate-cert/secure}", null, null, 0, 0, []], [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getLastError', null, ['Too many login failures']);
        $fake->add('getAlerts', null, [null]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/ssl/tls/validate-cert/secure}', '*'], [[]]);
        $fake->add('close', null, [null]);
        $imap = new ImapHandlerFake($fake);

        $_REQUEST['ssl'] = 1;

        $ie = new InboundEmail($imap);
        $ret = $ie->connectMailserver(true);
        $this->assertEquals(null, $ret);
    }

    public function testConnectMailserverUseSsl()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/notls/novalidate-cert/secure}', '*'], [[]]);
        $fake->add('ping', null, [true]);
        $fake->add('reopen', ['{:/service=}', 32768, 0], [true]);
        $imap = new ImapHandlerFake($fake);

        $_REQUEST['ssl'] = 1;

        $ie = new InboundEmail($imap);
        $ret = $ie->connectMailserver();
        $this->assertEquals('true', $ret);
    }

    public function testConnectMailserverNoImap()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [false]);
        $imap = new ImapHandlerFake($fake);

        $ie = new InboundEmail($imap);
        $ret = $ie->connectMailserver();
        $this->assertEquals(null, $ret);
    }

    public function testFindOptimumSettingsFalsePositive()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);  // <-- when the code calls ImapHandlerInterface::isAvailable([null]), it will return true
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('open', ["{:/service=/notls/novalidate-cert/secure}", null, null, 0, 0, []], [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getLastError', null, ["SECURITY PROBLEM: insecure server advertised AUTH=PLAIN"]);
        $fake->add('getAlerts', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/notls/novalidate-cert/secure}', '*'], [[]]);
        $fake->add('close', null, [null]);

        $exp = [
            'serial' => '::::::::novalidate-cert::notls::secure',
            'service' => 'foo/notls/novalidate-cert/secure',
        ];

        $imap = new ImapHandlerFake($fake);
        $inboundEmail = new InboundEmail($imap);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);

        // other errors also cause same results..

        $fake->add('getLastError', null, ["Mailbox is empty"]);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);
    }

    public function testFindOptimumSettingsFail()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);  // <-- when the code calls ImapHandlerInterface::isAvailable([null]), it will return true
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('open', ["{:/service=/notls/novalidate-cert/secure}", null, null, 0, 0, []], [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getLastError', null, ['Too many login failures']);
        $fake->add('getAlerts', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/notls/novalidate-cert/secure}', '*'], [[]]);
        $fake->add('close', null, [null]);

        $exp = [
            'good' => [],
            'bad' => ['both-secure' => '{:/service=/notls/novalidate-cert/secure}'],
            'err' => ['both-secure' => null],
        ];

        $imap = new ImapHandlerFake($fake);
        $inboundEmail = new InboundEmail($imap);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);

        // other errors also cause same results..

        $fake->add('getLastError', null, ['[CLOSED] IMAP connection broken (server response)']);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);

        $fake->add('getLastError', null, ['[AUTHENTICATIONFAILED]']);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);

        $fake->add('getLastError', null, ['something.. AUTHENTICATE .. something .. failed .. something']);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals($exp, $ret);
    }

    public function testFindOptimumSettingsOk()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);  // <-- when the code calls ImapHandlerInterface::isAvailable([null]), it will return true
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('open', ["{:/service=/notls/novalidate-cert/secure}", null, null, 0, 0, []], [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getLastError', null, [false]);
        $fake->add('getAlerts', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/notls/novalidate-cert/secure}', '*'], [[]]);
        $fake->add('close', null, [null]);

        $imap = new ImapHandlerFake($fake);
        $inboundEmail = new InboundEmail($imap);
        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals([
            'serial' => '::::::::novalidate-cert::notls::secure',
            'service' => 'foo/notls/novalidate-cert/secure',
        ], $ret);
    }

    public function testFindOptimumSettingsNoImap()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [false]);
        $imap = new ImapHandlerFake($fake);

        $ie = new InboundEmail($imap);
        $ret = $ie->findOptimumSettings();
        $this->assertEquals([
            'good' => [],
            'bad' => [],
            'err' => [null],
        ], $ret);
    }

    public function testFindOptimumSettingsUseSsl()
    {
        $fake = new ImapHandlerFakeData();
        $fake->add('isAvailable', null, [true]);
        $fake->add('setTimeout', [1, 60], [true]);
        $fake->add('setTimeout', [2, 60], [true]);
        $fake->add('setTimeout', [3, 60], [true]);
        $fake->add('getErrors', null, [false]);
        $fake->add('open', ["{:/service=/ssl/tls/validate-cert/secure}", null, null, 0, 0, []], [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getLastError', null, [false]);
        $fake->add('getAlerts', null, [false]);
        $fake->add('getConnection', null, [function () {
            return tempFileWithMode('wb+');
        }]);
        $fake->add('getMailboxes', ['{:/service=/ssl/tls/validate-cert/secure}', '*'], [[]]);
        $fake->add('close', null, [null]);

        $imap = new ImapHandlerFake($fake);

        $_REQUEST['ssl'] = 1;

        $ie = new InboundEmail($imap);
        $ret = $ie->findOptimumSettings();
        $this->assertEquals([
            'serial' => 'tls::::ssl::::::::secure',
            'service' => 'foo/ssl/tls/validate-cert/secure',
        ], $ret);
    }

    // ------------------------------------------------------------
    // ----- FOLLOWIN TESTS ARE USING REAL IMAP SO SHOULD FAIL ----
    // ---------------------------------------------------------------->

    public function testInboundEmail()
    {
        // Execute the constructor and check for the Object type and  attributes
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertInstanceOf('InboundEmail', $inboundEmail);
        $this->assertInstanceOf('SugarBean', $inboundEmail);

        $this->assertAttributeEquals('InboundEmail', 'module_dir', $inboundEmail);
        $this->assertAttributeEquals('InboundEmail', 'object_name', $inboundEmail);
        $this->assertAttributeEquals('inbound_email', 'table_name', $inboundEmail);

        $this->assertAttributeEquals(true, 'new_schema', $inboundEmail);
        $this->assertAttributeEquals(true, 'process_save_dates', $inboundEmail);

        $this->assertAttributeEquals('defaultIEAccount', 'keyForUsersDefaultIEAccount', $inboundEmail);
        $this->assertAttributeEquals(10, 'defaultEmailNumAutoreplies24Hours', $inboundEmail);
        $this->assertAttributeEquals(10, 'maxEmailNumAutoreplies24Hours', $inboundEmail);

        $this->assertAttributeEquals('InboundEmail.cache.php', 'InboundEmailCacheFile', $inboundEmail);

        $this->assertAttributeEquals('date', 'defaultSort', $inboundEmail);
        $this->assertAttributeEquals('DESC', 'defaultDirection', $inboundEmail);
        $this->assertAttributeEquals('F', 'iconFlagged', $inboundEmail);
        $this->assertAttributeEquals('D', 'iconDraft', $inboundEmail);
        $this->assertAttributeEquals('A', 'iconAnswered', $inboundEmail);
        $this->assertAttributeEquals('del', 'iconDeleted', $inboundEmail);
        $this->assertAttributeEquals(false, 'isAutoImport', $inboundEmail);

        $this->assertAttributeEquals(0, 'attachmentCount', $inboundEmail);
    }

    public function testsaveAndOthers()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->name = 'test';
        $inboundEmail->group_id = 1;
        $inboundEmail->status = 'Active';
        $inboundEmail->email_user = 'testuser';
        $inboundEmail->email_password = 'testpass';
        $inboundEmail->mailbox = 'mailbox1,mailbox2,mailbox3';

        $inboundEmail->save();

        //test for record ID to verify that record is saved
        $this->assertTrue(isset($inboundEmail->id));
        $this->assertEquals(36, strlen($inboundEmail->id));

        //test getCorrectMessageNoForPop3 method
        $this->getCorrectMessageNoForPop3($inboundEmail->id);

        //test retrieve method
        $this->retrieve($inboundEmail->id);

        //test retrieveByGroupId method
        $this->retrieveByGroupId($inboundEmail->group_id);

        //test retrieveAllByGroupId method
        $this->retrieveAllByGroupId($inboundEmail->group_id);

        //test retrieveAllByGroupIdWithGroupAccounts method
        $this->retrieveAllByGroupIdWithGroupAccounts($inboundEmail->group_id);

        //test getSingularRelatedId method
        $this->getSingularRelatedId();

        //test renameFolder method
        $this->renameFolder($inboundEmail->id);

        //test search method
        $this->search($inboundEmail->id);

        //test saveMailBoxFolders method
        $this->saveMailBoxFolders($inboundEmail->id);

        //test saveMailBoxValueOfInboundEmail method
        $this->saveMailBoxValueOfInboundEmail($inboundEmail->id);

        //test mark_deleted method
        $this->mark_deleted($inboundEmail->id);

        //test hardDelete method
        $this->hardDelete($inboundEmail->id);
    }

    public function getSingularRelatedId()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getSingularRelatedId('test', 'inbound_email');
        $this->assertEquals(false, $result);

        $result = $inboundEmail->getSingularRelatedId('invalid test', 'inbound_email');
        $this->assertEquals(null, $result);
    }

    public function getCorrectMessageNoForPop3($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->retrieve($id);

        $result = $inboundEmail->getCorrectMessageNoForPop3('100');
        $this->assertEquals(-1, $result);

        $result = $inboundEmail->getCorrectMessageNoForPop3('1');
        $this->assertEquals(-1, $result);
    }

    public function retrieve($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->retrieve($id);

        $this->assertEquals('test', $inboundEmail->name);
        $this->assertEquals('Active', $inboundEmail->status);
        $this->assertEquals('testuser', $inboundEmail->email_user);
        $this->assertEquals('testpass', $inboundEmail->email_password);
    }

    public function retrieveByGroupId($group_id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->retrieveByGroupId($group_id);

        $this->assertTrue(is_array($result));

        foreach ($result as $ie) {
            $this->assertInstanceOf('InboundEmail', $ie);
        }
    }

    public function retrieveAllByGroupId($group_id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->retrieveAllByGroupId($group_id);

        $this->assertTrue(is_array($result));

        foreach ($result as $ie) {
            $this->assertInstanceOf('InboundEmail', $ie);
        }
    }

    public function retrieveAllByGroupIdWithGroupAccounts($group_id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->retrieveAllByGroupIdWithGroupAccounts($group_id);

        $this->assertTrue(is_array($result));

        foreach ($result as $ie) {
            $this->assertInstanceOf('InboundEmail', $ie);
        }
    }

    public function renameFolder($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->retrieve($id);
        $this->assertFalse((bool)$inboundEmail->conn);

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $success = $inboundEmail->renameFolder('mailbox1', 'new_mailbox');
            $this->assertFalse((bool)$success);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function search($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->retrieve($id);

        $result = $inboundEmail->search($id);

        $this->assertTrue(is_array($result));
        $this->assertEquals('Search Results', $result['mbox']);
        $this->assertEquals($id, $result['ieId']);
    }

    public function saveMailBoxFolders($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->retrieve($id);

        //execute he method and verify attributes
        $inboundEmail->saveMailBoxFolders('INBOX,TRASH');
        $this->assertEquals(array('INBOX', 'TRASH'), $inboundEmail->mailboxarray);

        //retrieve it back and verify the updates
        $inboundEmail->retrieve($id);
        $this->assertEquals('INBOX,TRASH', $inboundEmail->mailbox);
    }

    public function saveMailBoxValueOfInboundEmail($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->email_user = 'TEST';

        $inboundEmail->saveMailBoxValueOfInboundEmail();

        //retrieve it back and verify the updates
        $inboundEmail->retrieve($id);
        $this->assertEquals('INBOX,TRASH', $inboundEmail->mailbox);
    }

    public function mark_deleted($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mark_deleted($id);

        $result = $inboundEmail->retrieve($id);
        $this->assertEquals(null, $result);
    }

    public function hardDelete($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->hardDelete($id);

        $result = $inboundEmail->retrieve($id);
        $this->assertEquals(null, $result);
    }

    public function testcustomGetMessageText()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->customGetMessageText('some message');
        $this->assertEquals('some message', $result);
    }

    public function testgetFormattedRawSource()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without ID
        $result = $inboundEmail->getFormattedRawSource('1');
        $this->assertEquals('This information is not available', $result);

        //test with ID
        $inboundEmail->id = 1;
        $result = $inboundEmail->getFormattedRawSource('1');
        $this->assertEquals('', $result);
    }

    public function testfilterMailBoxFromRaw()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with array having common element
        $result = $inboundEmail->filterMailBoxFromRaw(array('mailbox1', 'mailbox2', 'mailbox3'), array('mailbox1'));
        $this->assertSame(array('mailbox1'), $result);

        //test with array having nothing common
        $result = $inboundEmail->filterMailBoxFromRaw(array('mailbox1', 'mailbox2'), array('mailbox4'));
        $this->assertSame(array(), $result);
    }

    public function testconvertToUtf8()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');
        $result = $inboundEmail->convertToUtf8('some text with non UTF8 chars');
        $this->assertSame('some text with non UTF8 chars', $result);
    }

    public function testgetFormattedHeaders()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test for default/imap
        $result = $inboundEmail->getFormattedHeaders(1);
        $this->assertSame(null, $result);

        //test for pop3
        $inboundEmail->protocol = 'pop3';
        $result = $inboundEmail->getFormattedHeaders(1);
        $this->assertSame(null, $result);
    }

    public function testsetAndgetCacheTimestamp()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        //test setCacheTimestamp method
        $inboundEmail->setCacheTimestamp('INBOX');

        //test getCacheTimestamp method
        $result = $inboundEmail->getCacheTimestamp('INBOX');
        $this->assertGreaterThan(0, strlen($result));
    }

    private function setDummyCacheValue()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        $inserts = array();

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);
        $inboundEmail->setCacheValue('INBOX.Trash', $inserts);
        return $inboundEmail;
    }

    public function testsetCacheValue()
    {
        $inboundEmail = $this->setDummyCacheValue();

        //retrieve back to verify the records created
        $result = $inboundEmail->getCacheValue('INBOX');

        $this->assertGreaterThan(0, count((array)$result['retArr'][0]));
        $this->assertEquals(1, $result['retArr'][0]->message_id);
    }

    public function testgetCacheValueForUIDs()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test wih default protocol
        $result = $inboundEmail->getCacheValueForUIDs('INBOX', array(1, 2, 3, 4, 5));

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result['uids']));
        $this->assertTrue(is_array($result['retArr']));

        //test wih pop3 protocol
        $inboundEmail->protocol = 'pop3';
        $result = $inboundEmail->getCacheValueForUIDs('INBOX', array(1, 2, 3, 4, 5));

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result['uids']));
        $this->assertTrue(is_array($result['retArr']));
    }

    public function testgetCacheValue()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test wih default protocol
        $result = $inboundEmail->getCacheValue('INBOX');

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result['uids']));
        $this->assertTrue(is_array($result['retArr']));

        //test wih pop3 protocol
        $inboundEmail->protocol = 'pop3';
        $result = $inboundEmail->getCacheValue('INBOX');

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result['uids']));
        $this->assertTrue(is_array($result['retArr']));
    }

    public function testValidCacheExists()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without a valid id
        $result = $inboundEmail->validCacheExists('');
        $this->assertEquals(false, $result);

        //test with a valid id set
        $inboundEmail = $this->setDummyCacheValue();
        $result = $inboundEmail->validCacheExists('');
        $this->assertEquals(true, $result);

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        $result = $inboundEmail->validCacheExists('INBOX');
        $this->assertEquals(true, $result);
    }

    public function testdisplayFetchedSortedListXML()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //get the cache values array first
        $inboundEmail->id = 1;
        $ret = $inboundEmail->getCacheValue('INBOX');

        //use the cache values array as parameter and verify that it returns an array
        $result = $inboundEmail->displayFetchedSortedListXML($ret, 'INBOX');
        $this->assertTrue(is_array($result));
    }

    public function testgetCacheUnreadCount()
    {
        $inboundEmail = $this->setDummyCacheValue();

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        //test with invalid mailbox
        $result = $inboundEmail->getCacheUnreadCount('OUTBOX');
        $this->assertEquals(0, $result);

        //test with valid mailbox
        $result = $inboundEmail->getCacheUnreadCount('INBOX');
        $this->assertGreaterThanOrEqual(1, $result);
    }

    public function testgetCacheCount()
    {
        $inboundEmail = $this->setDummyCacheValue();

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        //test with invalid mailbox
        $result = $inboundEmail->getCacheCount('OUTBOX');
        $this->assertEquals(0, $result);

        //test with valid mailbox
        $result = $inboundEmail->getCacheCount('INBOX');
        $this->assertGreaterThanOrEqual(1, $result);
        

    }

    public function testgetCacheUnread()
    {
        // test
        $inboundEmail = $this->setDummyCacheValue();

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        //test with invalid mailbox
        $result = $inboundEmail->getCacheUnread('OUTBOX');
        $this->assertEquals(0, $result);

        //test with valid mailbox
        $result = $inboundEmail->getCacheUnread('INBOX');
        $this->assertGreaterThanOrEqual(1, $result);
    }

    public function testmark_answered()
    {
        $inboundEmail = $this->setDummyCacheValue();

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        //execute the method to populate answered field
        $inboundEmail->mark_answered(1, 'pop3');

        //retrieve back to verify the records updated
        $result = $inboundEmail->getCacheValue('INBOX');

        $this->assertEquals(1, $result['retArr'][0]->answered);
    }

    public function testpop3_shiftCache()
    {
        $inboundEmail = $this->setDummyCacheValue();

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        $result = $inboundEmail->pop3_shiftCache(array('1' => '1'), array('1'));

        //retrieve back to verify the records updated
        $result = $inboundEmail->getCacheValue('INBOX');

        $this->assertEquals(1, $result['retArr'][0]->imap_uid);
        $this->assertEquals(1, $result['retArr'][0]->msgno);
    }

    public function testgetUIDLForMessage()
    {
        $inboundEmail = $this->setDummyCacheValue();

        $inboundEmail->pop3_shiftCache(array('1' => '1'), array('1'));

        //test with invalid msgNo
        $result = $inboundEmail->getUIDLForMessage('2');
        $this->assertEquals('', $result);
    }

    public function testgetMsgnoForMessageID()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        //test with invalid msgNo
        $result = $inboundEmail->getMsgnoForMessageID('2');
        $this->assertEquals('', $result);

        //test with valid msgNo but most probably it will never work because of wrong column name in return statement
        $result = $inboundEmail->getMsgnoForMessageID('1');
        $this->assertEquals('', $result);
    }

    public function testpop3_getCacheUidls()
    {
        $inboundEmail = $this->setDummyCacheValue();
        $inboundEmail->pop3_shiftCache(array('1' => '1'), array('1'));

        $result = $inboundEmail->pop3_getCacheUidls();

        $this->assertEquals(array('1' => '1'), $result);
    }

    /**
     * @todo: NEEDS REVISION
     */
    public function testsetStatuses()
    {
//        $this->markTestIncomplete("Different results for php5 and php7");
//        /*
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->id = 1;
//        $inboundEmail->mailbox = 'INBOX';
//
//        //execute the method
//        $inboundEmail->setStatuses('1', 'message_id', '123');
//
//        //retrieve back to verify the records created
//        $result = $inboundEmail->getCacheValueForUIDs('INBOX', array(1));
//
//        $this->assertTrue(is_array($result));
//        $this->assertEquals('123', $result['retArr'][0]->message_id);
//        */
    }

    /**
     * @todo: NEEDS REVISION
     */
    public function testdeleteMessageFromCache()
    {
//        $this->markTestIncomplete("Unable to test until testsetStatuses is re-enabled");
//        /*
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->id = 1;
//        $inboundEmail->mailbox = 'INBOX';
//        $inboundEmail->protocol = 'pop3';
//
//        $inboundEmail->deleteMessageFromCache('123');
//
//        //retrieve back to verify the records deleted
//        $result = $inboundEmail->getCacheValueForUIDs('INBOX', array(1));
//
//        $this->assertTrue(is_array($result));
//        $this->assertEquals(0, count($result['retArr']));
//        */
    }

    public function testemptyTrash()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        $inboundEmail->emptyTrash();

        $result = $inboundEmail->getCacheValue('INBOX.Trash');
        $this->assertEquals(0, count($result['retArr']));
    }

    public function testdeleteCache()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        $inserts = [];

        $overview = new Overview();
        $overview->imap_uid = 1;
        $overview->subject = 'subject';
        $overview->from = 'from';
        $overview->fromaddr = 'from@email.com';
        $overview->to = 'to';
        $overview->toaddr = 'to@email.com';
        $overview->size = 0;
        $overview->message_id = 1;

        $inserts[] = $overview;

        //execute the method to populate email cache
        $inboundEmail->setCacheValue('INBOX', $inserts);

        $inboundEmail->deleteCache();

        $result = $inboundEmail->getCacheValue('INBOX');
        $this->assertEquals(0, count($result['retArr']));
    }

    public function testdeletePop3Cache()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->deletePop3Cache();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testpop3_open()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->pop3_open();

        $this->assertEquals(false, $result);
    }

    public function testpop3_cleanUp()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->pop3_cleanUp();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testpop3_sendCommand()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->pop3_sendCommand('get');

        $this->assertEquals('', $result);
    }

    public function testgetPop3NewMessagesToDownload()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->getPop3NewMessagesToDownload();

        $this->assertTrue(is_array($result));
    }

    public function testgetPop3NewMessagesToDownloadForCron()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->getPop3NewMessagesToDownloadForCron();

        $this->assertTrue(is_array($result));
    }

    public function testpop3_getUIDL()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';
        $inboundEmail->protocol = 'pop3';

        $result = $inboundEmail->getPop3NewMessagesToDownloadForCron();

        $this->assertTrue(is_array($result));
    }

    public function testpop3_checkPartialEmail()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';
        $inboundEmail->protocol = 'pop3';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->pop3_checkPartialEmail();
            $this->assertEquals('could not open socket connection to POP3 server', $result);

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testpop3_checkEmail()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';
        $inboundEmail->protocol = 'pop3';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->pop3_checkEmail();
            $this->assertEquals(false, $result);

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testgetMessagesInEmailCache()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        //test for IMAP
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->getMessagesInEmailCache(0, 1);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }

        //test for pop3
        $inboundEmail->protocol = 'pop3';
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->getMessagesInEmailCache(1, 0);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testcheckEmailOneMailbox()
    {
//        $this->markTestIncomplete('this test failing only on php 7.2');
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->mailbox = 'INBOX,OUTBOX';
//
//        $result = $inboundEmail->checkEmailOneMailbox('INBOX');
//        $this->assertEquals(1, $result);
    }

    public function testcheckEmailOneMailboxPartial()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->checkEmailOneMailboxPartial('INBOX');

        $this->assertEquals(array('status' => 'done'), $result);
    }

    public function testgetCachedIMAPSearch()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->getCachedIMAPSearch('test');

        $this->assertTrue(is_array($result));
    }

    public function testcheckEmailIMAPPartial()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->checkEmailIMAPPartial();

        $this->assertTrue(is_array($result));
    }

    public function testcheckEmail2_meta()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->checkEmail2_meta();

        $this->assertTrue(is_array($result));
        $this->assertEquals(array('mailboxes' => array('INBOX' => 0), 'processCount' => 0), $result);
    }

    public function testgetMailboxProcessCount()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getMailboxProcessCount('INBOX');

        $this->assertEquals(0, $result);
    }

    public function testcheckEmail()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test for IMAP
        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->checkEmail('INBOX');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }

        //test for pop3
        $inboundEmail->protocol = 'pop3';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->checkEmail('INBOX');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testsyncEmail()
    {
        global $current_user;
        $current_user = new User('1');

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->syncEmail();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testdeleteCachedMessages()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->deleteCachedMessages('1,2', 'test');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testgetOverviewsFromCacheFile()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getOverviewsFromCacheFile('1,2', 'INBOX');

        $this->assertTrue(is_array($result));
    }

    /**
     * @todo: NEEDS REVISION
     */
    public function testupdateOverviewCacheFile()
    {
//        $this->markTestIncomplete("Different results for php5 and php7");
//        /*
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->id = 1;
//        $inboundEmail->mailbox = 'INBOX';
//
//        $overview = new Overview();
//        $overview->subject = 'subject 1';
//        $overview->size = '10001';
//        $overview->uid = '1';
//
//        $overviews = array($overview);
//
//        $inboundEmail->updateOverviewCacheFile($overviews);
//
//        //retrieve back to verify the records created
//        $result = $inboundEmail->getCacheValue('INBOX');
//        $this->assertGreaterThan(0, count($result['retArr'][0]));
//        $this->assertEquals('subject 1', $result['retArr'][0]->subject);
//        */
    }

    public function testsetReadFlagOnFolderCache()
    {
//        $this->markTestIncomplete('Undefined offset: 0');
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        $inboundEmail->id = 1;
////
////        $inboundEmail->setReadFlagOnFolderCache('INBOX', '1');
////
////        //retrieve back to verify the records updated
////        $result = $inboundEmail->getCacheValue('INBOX');
////        $this->assertEquals(0, $result['retArr'][0]->seen);
    }

    public function testfetchCheckedEmails()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;
        $inboundEmail->mailbox = 'INBOX';

        //test with size over 1000 and no imap_uid
        $overview1 = new Overview();
        $overview1->subject = 'subject 1';
        $overview1->size = '10001';

        $fetchedOverviews = array($overview1);
        $result = $inboundEmail->fetchCheckedEmails($fetchedOverviews);

        $this->assertEquals(false, $result);

        //test with size less than 1000 and imap_uid set
        $overview2 = new Overview();
        $overview2->subject = 'subject 2';
        $overview2->size = '100';
        //$overview2->imap_uid = 1; //dies if imap_uid is set

        $fetchedOverviews = array($overview2);
        $result = $inboundEmail->fetchCheckedEmails($fetchedOverviews);

        $this->assertEquals(true, $result);
    }

    public function testmarkEmails()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->markEmails('1', 'unread');
            $inboundEmail->markEmails('1', 'read');
            $inboundEmail->markEmails('1', 'flagged');
            $inboundEmail->markEmails('1', 'unflagged');
            $inboundEmail->markEmails('1', 'answered');

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testdeleteFolder()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX';

        $result = $inboundEmail->deleteFolder('INBOX');

        $this->assertSame(['status', 'errorMessage'], array_keys($result));
        $this->assertFalse($result['status']);
    }

    public function testsaveNewFolder()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->saveNewFolder('TEST', 'INBOX');

        $this->assertEquals(false, $result);
    }

    public function testgetImapMboxFromSugarProprietary()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with invalid format string
        $result = $inboundEmail->getImapMboxFromSugarProprietary('INBOX.TRASH');
        $this->assertEquals('', $result);

        //test with valid format but shorter string
        $result = $inboundEmail->getImapMboxFromSugarProprietary('INBOX::TRASH');
        $this->assertEquals('', $result);

        //test with valid format longer string
        $result = $inboundEmail->getImapMboxFromSugarProprietary('INBOX::TRASH::TEST');
        $this->assertEquals('TEST', $result);
    }

    public function testrepairAccount()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->email_password = 'test_pass';

        $result = $inboundEmail->repairAccount();

        $this->assertEquals(false, $result);
    }

    public function testgetTeamSetIdForTeams()
    {
//        $this->markTestIncomplete("Fatal error: Class 'Team' not found");
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        //$result = $inboundEmail->getTeamSetIdForTeams("1");
////
////        //test for record ID to verify that record is saved
////        //$this->assertTrue(isset($result));
////        //$this->assertEquals(36, strlen($result));
    }

    public function testsavePersonalEmailAccountAndOthers()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $_REQUEST['ie_name'] = 'test';
        $_REQUEST['ie_status'] = 'Active';
        $_REQUEST['server_url'] = '';
        $_REQUEST['email_user'] = 'test';
        $_REQUEST['email_password'] = 'test_pass';
        $_REQUEST['mailbox'] = 'INBOX';

        $result = $inboundEmail->savePersonalEmailAccount(1, 'admin', true);

        $this->assertTrue(isset($inboundEmail->id));
        $this->assertEquals(36, strlen($inboundEmail->id));

        //test handleIsPersonal method
        $this->handleIsPersonal($inboundEmail->id);

        //test getUserPersonalAccountCount method
        $this->getUserPersonalAccountCount();

        //test retrieveByGroupFolderId method
        $this->retrieveByGroupFolderId();

        //test getUserNameFromGroupId method
        $this->getUserNameFromGroupId($inboundEmail->id);

        //test deletePersonalEmailAccount method
        $this->deletePersonalEmailAccount($inboundEmail->id);
    }

    public function handleIsPersonal($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with a invalid group_id
        $inboundEmail->group_id = 2;
        $result = $inboundEmail->handleIsPersonal();
        $this->assertEquals(false, $result);

        //test with a valid group_id
        $inboundEmail->retrieve($id);
        $result = $inboundEmail->handleIsPersonal();
        $this->assertEquals(true, $result);
    }

    public function getUserPersonalAccountCount()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with invalid user id
        $user = BeanFactory::newBean('Users');
        $result = $inboundEmail->getUserPersonalAccountCount($user);
        $this->assertEquals(0, $result);

        //test with valid user id
        $user->id = 1;
        $result = $inboundEmail->getUserPersonalAccountCount($user);
        $this->assertGreaterThan(0, $result);
    }

    public function retrieveByGroupFolderId()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with invalid groupfolder id
        $result = $inboundEmail->retrieveByGroupFolderId('1');

        $this->assertTrue(is_array($result));
        $this->assertEquals(0, count($result));

        //test with valid groupfolder id
        $result = $inboundEmail->retrieveByGroupFolderId('');

        $this->assertTrue(is_array($result));
        foreach ($result as $ie) {
            $this->assertInstanceOf('InboundEmail', $ie);
        }
    }

    public function getUserNameFromGroupId($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with a invalid group_id
        $inboundEmail->group_id = 2;
        $result = $inboundEmail->getUserNameFromGroupId();
        $this->assertEquals('', $result);

        //test with a valid group_id
        $inboundEmail->retrieve($id);
        $result = $inboundEmail->getUserNameFromGroupId();
        $this->assertEquals('admin', $result);
    }

    public function deletePersonalEmailAccount($id)
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with invalid username
        $result = $inboundEmail->deletePersonalEmailAccount($id, 'test');
        $this->assertEquals(false, $result);

        //test with valid username
        $result = $inboundEmail->deletePersonalEmailAccount($id, 'admin');
        $this->assertEquals(true, $result);
    }

    public function testgetFoldersListForMailBox()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getFoldersListForMailBox();
        $this->assertTrue(is_array($result));
    }

    public function testfindOptimumSettings()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with different parameters, it will always return false because we do not have a mail server to connect.

        $ret = $inboundEmail->findOptimumSettings();
        $this->assertEquals(false, $ret);

        $this->assertEquals(false, $inboundEmail->findOptimumSettings(true));

        $this->assertEquals(false, $inboundEmail->findOptimumSettings(false, 'test', 'test', '', '', 'INBOX'));
    }

    public function testgetSessionConnectionString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting session key
        $result = $inboundEmail->getSessionConnectionString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('', $result);

        //test with session key set
        $_SESSION['mail.google.comtest22IMAP'] = 'test connection string';
        $result = $inboundEmail->getSessionConnectionString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('test connection string', $result);
    }

    public function testsetSessionConnectionString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->setSessionConnectionString('mail.google.com', 'test', 22, 'IMAP', 'test connection');
        $this->assertEquals('test connection', $_SESSION['mail.google.comtest22IMAP']);
    }

    public function testgetSessionInboundDelimiterString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting session key
        $result = $inboundEmail->getSessionInboundDelimiterString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('', $result);

        //test with session key set
        $_SESSION['mail.google.comtest22IMAPdelimiter'] = 'delimit string';
        $result = $inboundEmail->getSessionInboundDelimiterString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('delimit string', $result);
    }

    public function testsetSessionInboundDelimiterString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->setSessionInboundDelimiterString('mail.google.com', 'test', 22, 'IMAP', 'test string');
        $this->assertEquals('test string', $_SESSION['mail.google.comtest22IMAPdelimiter']);
    }

    public function testgetSessionInboundFoldersString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting session key
        $result = $inboundEmail->getSessionInboundFoldersString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('', $result);

        //test with session key set
        $_SESSION['mail.google.comtest22IMAPfoldersList'] = 'foldersList string';
        $result = $inboundEmail->getSessionInboundFoldersString('mail.google.com', 'test', 22, 'IMAP');
        $this->assertEquals('foldersList string', $result);
    }

    public function testsetSessionInboundFoldersString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->setSessionInboundFoldersString('mail.google.com', 'test', 22, 'IMAP', 'foldersList string');
        $this->assertEquals('foldersList string', $_SESSION['mail.google.comtest22IMAPfoldersList']);
    }

    public function testgroupUserDupeCheck()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without name i-e user_name in query
        $result = $inboundEmail->groupUserDupeCheck();
        $this->assertEquals(false, $result);

        //test with name i-e user_name in query
        $inboundEmail->name = 'admin';
        $result = $inboundEmail->groupUserDupeCheck();
        $this->assertEquals(false, $result);
    }

    public function testgetGroupsWithSelectOptions()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->group_id = 1;

        $result = $inboundEmail->getGroupsWithSelectOptions();
        $this->assertEquals('', $result);

        $expected = "\n<OPTION value='0'>1</OPTION>\n<OPTION selected value='1'>2</OPTION>\n<OPTION value='2'>3</OPTION>";
        $result = $inboundEmail->getGroupsWithSelectOptions(array(1, 2, 3));
        $this->assertEquals($expected, $result);
    }

    public function testhandleAutoresponse()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->template_id = 1;
        $email = BeanFactory::newBean('Emails');
        $email->name = 'test';
        $email->from_addr = 'test@email.com';
        $contactAddr = 'test@email.com';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->handleAutoresponse($email, $contactAddr);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testhandleCaseAssignment()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $email = BeanFactory::newBean('Emails');
        $email->name = 'test';

        $result = $inboundEmail->handleCaseAssignment($email);
        $this->assertEquals(false, $result);
    }

    public function testhandleMailboxType()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $email = BeanFactory::newBean('Emails');
        $email->name = 'test';

        $inboundEmail->mailbox_type = 'support';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->handleMailboxType($email, $header);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testisMailBoxTypeCreateCase()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting attributes
        $result = $inboundEmail->isMailBoxTypeCreateCase();
        $this->assertEquals(false, $result);

        //test with attributes set
        $inboundEmail->mailbox_type = 'createcase';
        $inboundEmail->groupfolder_id = 1;

        $result = $inboundEmail->isMailBoxTypeCreateCase();
        $this->assertEquals(true, $result);
    }

    public function testhandleCreateCase()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $email = BeanFactory::newBean('Emails');
        $email->name = 'test';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->handleCreateCase($email, 1);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testhandleLinking()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $email = BeanFactory::newBean('Emails');
        $email->from_addr = 'test@from.com';

        $result = $inboundEmail->handleLinking($email);
        $this->assertEquals($email->from_addr, $result);
    }

    public function testgetEncodingFromBreadCrumb()
    {
//        $this->markTestIncomplete('errors in method');
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        $parts = array(
////                    (Object) array('encoding' => 'utf-8', 'parts' => array((Object) array('encoding' => 'utf-8', 'parts' => array((Object) array('encoding' => 'utf-8', 'parts' => 'dummy parts 2'))))),
////                );
////
////        //$result = $inboundEmail->getEncodingFromBreadCrumb("1.2.3", $parts);
////
////        //$this->assertEqilas('utf-8', $result);
    }

    public function testgetCharsetFromBreadCrumb()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $parts = array(
                (Object) array('ifparameters' => 1, 'attribute' => 'charset', 'value' => 'test', 'parts' => array((Object) array('ifparameters' => 1, 'attribute' => 'charset', 'value' => 'test', 'parts' => array((Object) array('ifparameters' => 1, 'attribute' => 'charset', 'value' => 'test', 'parts' => 'dummy parts 2'))))),
        );

        $result = $inboundEmail->getCharsetFromBreadCrumb('1.2.3', $parts);

        $this->assertEquals('default', $result);
    }

    public function testgetMessageTextFromSingleMimePart()
    {
//        $this->markTestIncomplete('Exception: PHPUnit_Framework_Error_Notice: Undefined variable: structure');
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        // Execute the method and test that it works and doesn't throw an exception.
////        try {
////            $result = $inboundEmail->getMessageTextFromSingleMimePart(1, 1, $structure);
////            $this->assertTrue(true);
////        } catch (Exception $e) {
////            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
////        }
    }

    public function testaddBreadCrumbOffset()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with empty offset string
        $result = $inboundEmail->addBreadCrumbOffset('1.1.1', '');
        $this->assertEquals('1.1.1', $result);

        //test with empty bread crumb string
        $result = $inboundEmail->addBreadCrumbOffset('', '1.1.1');
        $this->assertEquals('1.1.1', $result);

        //test with shorter bread crumb string
        $result = $inboundEmail->addBreadCrumbOffset('1.1.1', '2.2.2.2');
        $this->assertEquals('3.3.3.2', $result);
    }

    public function testgetMessageText()
    {
//        $this->markTestIncomplete('Exception: PHPUnit_Framework_Error_Notice: Undefined variable: structure');
//
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        // Execute the method and test that it works and doesn't throw an exception.
////        try {
////            $result = $inboundEmail->getMessageText(1, 'PLAIN', $structure, $fullHeader);
////            $this->assertTrue(true);
////        } catch (Exception $e) {
////            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
////        }
    }

    public function testdecodeHeader()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $expected = array(
                          'From' => 'Media Temple user (mt.kb.user@gmail.com)',
                          'Subject' => 'article: How to Trace a Email',
                          'Date' => 'January 25, 2011 3:30:58 PM PDT',
                          'To' => 'user@example.com',
                          'Return-Path' => '<mt.kb.user@gmail.com>',
                          'Envelope-To' => 'user@example.com',
                          'Delivery-Date' => 'Tue, 25 Jan 2011 15:31:01 -0700',
                        );
        $header = "From: Media Temple user (mt.kb.user@gmail.com)\r\nSubject: article: How to Trace a Email\r\nDate: January 25, 2011 3:30:58 PM PDT\r\nTo: user@example.com\r\nReturn-Path: <mt.kb.user@gmail.com>\r\nEnvelope-To: user@example.com\r\nDelivery-Date: Tue, 25 Jan 2011 15:31:01 -0700";

        $result = $inboundEmail->decodeHeader($header);
        $this->assertEquals($expected, $result);
    }

    public function testhandleCharsetTranslation()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with default
        $result = $inboundEmail->handleCharsetTranslation('sample text', 'default');
        $this->assertEquals('sample text', $result);

        //test with ISO-8859-8
        $result = $inboundEmail->handleCharsetTranslation('sample text', 'ISO-8859-8');
        $this->assertEquals('sample text', $result);
    }

    public function testbuildBreadCrumbs()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->buildBreadCrumbs(array(), 'ALTERNATIVE', '1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testbuildBreadCrumbsHTML()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->buildBreadCrumbsHTML(array());
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testconvertImapToSugarEmailAddress()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX';
        $inboundEmail->host = 'mail.google.com';

        $result = $inboundEmail->convertImapToSugarEmailAddress(array($inboundEmail));
        $this->assertEquals('INBOX@mail.google.com', $result);
    }

    public function testhandleEncodedFilename()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->handleEncodedFilename('attachment1.pdf');
        $this->assertEquals('attachment1.pdf', $result);
    }

    public function testgetMimeType()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertEquals('text/plain', $inboundEmail->getMimeType(0, 'plain'));
        $this->assertEquals('multipart/binary', $inboundEmail->getMimeType(1, 'binary'));
        $this->assertEquals('other/subtype', $inboundEmail->getMimeType('test', 'subtype'));
    }

    public function testsaveAttachments()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->saveAttachments('1', array(), '1', '0', true);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testgetNoteBeanForAttachment()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getNoteBeanForAttachment('1');

        $this->assertInstanceOf('Note', $result);
        $this->assertAttributeEquals('1', 'parent_id', $result);
        $this->assertAttributeEquals('Emails', 'parent_type', $result);
    }

    public function testretrieveAttachmentNameFromStructure()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with filename attribute
        $part = (Object) array('dparameters' => array((Object) array('attribute' => 'filename', 'value' => 'test1.txt'), (Object) array('attribute' => 'filename', 'value' => 'test2.txt')),
                                'parameters' => array((Object) array('attribute' => 'name', 'value' => 'test1'), (Object) array('attribute' => 'name', 'value' => 'test2')),
                                );

        $result = $inboundEmail->retrieveAttachmentNameFromStructure($part);
        $this->assertEquals('test1.txt', $result);

        //test with no filename attribute
        $part = (Object) array('dparameters' => array((Object) array('attribute' => 'name', 'value' => 'test1.txt')),
                                'parameters' => array((Object) array('attribute' => 'name', 'value' => 'test1'), (Object) array('attribute' => 'name', 'value' => 'test2')),

                                );

        $result = $inboundEmail->retrieveAttachmentNameFromStructure($part);
        $this->assertEquals('test1', $result);
    }

    public function testsaveAttachmentBinaries()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $part = (Object) array('disposition' => 'multipart', 'subtype' => 10);

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->saveAttachmentBinaries(BeanFactory::newBean('Notes'), '1', '1.1', $part, 1);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testhandleTranserEncoding()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertEquals('test', $inboundEmail->handleTranserEncoding('test'));
        $this->assertEquals('test', $inboundEmail->handleTranserEncoding('dGVzdA==', 3));
        $this->assertEquals('test', $inboundEmail->handleTranserEncoding('test', 4));
    }

    public function testgetMessageId()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $header = "From: Media Temple user (mt.kb.user@gmail.com)\r\nSubject: article: How to Trace a Email\r\nDate: January 25, 2011 3:30:58 PM PDT\r\nTo: user@example.com\r\nReturn-Path: <mt.kb.user@gmail.com>\r\nEnvelope-To: user@example.com\r\nDelivery-Date: Tue, 25 Jan 2011 15:31:01 -0700";

        $result = $inboundEmail->getMessageId($header);

        $this->assertEquals('21c65f7db176f0bd93768214b00ae397', $result);
    }

    public function testimportDupeCheck()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $textHeader = "From: Media Temple user (mt.kb.user@gmail.com)\r\nSubject: article: How to Trace a Email\r\nDate: January 25, 2011 3:30:58 PM PDT\r\nTo: user@example.com\r\nReturn-Path: <mt.kb.user@gmail.com>\r\nEnvelope-To: user@example.com\r\nDelivery-Date: Tue, 25 Jan 2011 15:31:01 -0700";

        $result = $inboundEmail->importDupeCheck('1', $textHeader, $textHeader);
        $this->assertEquals(true, $result);
    }

    public function testhandleMimeHeaderDecode()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->handleMimeHeaderDecode('Subject: article: How to Trace a Email');

        $this->assertEquals('Subject: article: How to Trace a Email', $result);
    }

    public function testgetUnixHeaderDate()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->handleMimeHeaderDecode('Date: January 25, 2011 3:30:58 PM PDT');

        $this->assertEquals('Date: January 25, 2011 3:30:58 PM PDT', $result);
    }

    public function testgetDuplicateEmailId()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->getDuplicateEmailId('1', '1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testimportOneEmail()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX';
        $inboundEmail->id = 1;

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $result = $inboundEmail->importOneEmail('1', '1');
            $this->assertEquals(false, $result);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testisUuencode()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertEquals(false, $inboundEmail->isUuencode('test'));

        $this->assertEquals(false, $inboundEmail->isUuencode("begin 0744 odt_uuencoding_file.dat\r+=&5S=\"!S=')I;F<`\r`\rend"));
    }

    public function testhandleUUEncodedEmailBody()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $raw = 'Message Body: This is a KnowledgeBase article that provides information on how to find email headers and use the data to trace a email.';

        $result = $inboundEmail->handleUUEncodedEmailBody($raw, 1);

        $this->assertEquals("\n".$raw, $result);
    }

    public function testhandleUUDecode()
    {
//        $this->markTestIncomplete('Uncaught require_once(include/PHP_Compat/convert_uudecode.php)');
//        /*
//        //unset and reconnect Db to resolve mysqli fetch exeception
//        $db = DBManagerFactory::getInstance();
//        unset ($db->database);
//        $db->checkConnection();
//
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $raw = "\nMessage Body: This is a KnowledgeBase article that provides information on how to find email headers and use the data to trace a email.";
//
//        $inboundEmail->handleUUDecode("1", "handleUUDecode_test", $raw);
//
//        */
    }

    public function testcheckFilterDomain()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $email = BeanFactory::newBean('Emails');
        $email->reply_to_email = 'reply@gmail.com';
        $email->from_addr = 'from@gmail.com';

        $result = $inboundEmail->checkFilterDomain($email);
        $this->assertEquals(true, $result);
    }

    public function testcheckOutOfOffice()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertEquals(false, $inboundEmail->checkOutOfOffice('currently Out of Office, will reply later'));
        $this->assertEquals(true, $inboundEmail->checkOutOfOffice('test subject'));
    }

    public function testsetAndgetAutoreplyStatus()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;

        //execute the setAutoreplyStatus method to set an auto reply status for email
        $inboundEmail->setAutoreplyStatus('auto_reply_test@email.com');

        //test with and invalid email. it will return true as well because it's stil under max limit.
        $result = $inboundEmail->getAutoreplyStatus('invalid@email.com');
        $this->assertEquals(true, $result);
    }

    public function testsaveInboundEmailSystemSettings()
    {
        global $sugar_config, $db;

        //unset and reconnect Db to resolve mysqli fetch exeception
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //execute the method to save the setting
        $inboundEmail->saveInboundEmailSystemSettings('test', 'test_macro');

        //verify the key created
        $this->assertEquals('test_macro', $sugar_config['inbound_email_test_subject_macro']);
    }

    public function testgetSystemSettingsForm()
    {
//        $this->markTestIncomplete("It should be an acceptance test");
//
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $expected = "<form action=\"index.php\" method=\"post\" name=\"Macro\" id=\"form\">
//    <input type=\"hidden\" name=\"module\" value=\"InboundEmail\">
//    <input type=\"hidden\" name=\"action\" value=\"ListView\">
//    <input type=\"hidden\" name=\"save\" value=\"true\">
//
//    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
//        <tr>
//            <td>
//                <input title=\"Save\"
//                          accessKey=\"a\"
//                          class=\"button\"
//                          onclick=\"this.form.return_module.value='InboundEmail'; this.form.return_action.value='ListView';\"
//                          type=\"submit\" name=\"Edit\" value=\"Save\">
//            </td>
//        </tr>
//    </table>
//
//    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"detail view\">
//        <tr>
//            <td valign=\"top\" width='10%' NOWRAP scope=\"row\">
//                <span>
//                    <b>:</b>
//                </span>
//            </td>
//            <td valign=\"top\" width='20%'>
//                <span>
//                    <input name=\"inbound_email_case_macro\" type=\"text\" value=\"[CASE:%1]\">
//                </span>
//            </td>
//            <td valign=\"top\" width='70%'>
//                <span>
//                    <br />
//                    <i></i>
//                </span>
//            </td>
//        </tr>
//    </table>
//</form>";
//        $result = $inboundEmail->getSystemSettingsForm();
//
//        $this->assertSame($expected, $result);
    }

    public function testgetCaseIdFromCaseNumber()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getCaseIdFromCaseNumber('test', BeanFactory::newBean('Cases'));
        $this->assertEquals(false, $result);
    }

    public function testget_stored_options()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->get_stored_options('test', '');
        $this->assertEquals('', $result);

        $result = $inboundEmail->get_stored_options('test', 'default_option');
        $this->assertEquals('default_option', $result);
    }

    public function testSetStoredOptions()
    {
        $ie = BeanFactory::newBean('InboundEmail');
        $so = $ie->getStoredOptions();
        $so['something'] = 'testinfo';
        $ie->setStoredOptions($so);
        $ret = $ie->getStoredOptions();
        $this->assertEquals('testinfo', $ret['something']);
    }

    public function testgetRelatedId()
    {
//        $this->markTestIncomplete('Undefined variable: result');
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        //test with Users module
////        $inboundEmail->getRelatedId('getRelatedId@email.com', 'Users');
////        $this->assertEquals(false, $result);
////
////        //test with Contacts module
////        $inboundEmail->getRelatedId('getRelatedId@email.com', 'Contacts');
////        $this->assertEquals(false, $result);
    }

    public function testgetNewMessageIds()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->getNewMessageIds();

        $this->assertEquals(null, $result);
    }

    public function testgetConnectString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $this->assertEquals('{:/service=}', $inboundEmail->getConnectString()); //test with default options
        $this->assertEquals('{:/service=mail.google.com}INBOX', $inboundEmail->getConnectString('mail.google.com', 'INBOX'));//test with includeMbox true
        $this->assertEquals('{:/service=mail.google.com}', $inboundEmail->getConnectString('mail.google.com', 'INBOX', false));//test with includeMbox false
    }

    public function testdisconnectMailserver()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->disconnectMailserver();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testconnectMailserver()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with default parameters
        $result = $inboundEmail->connectMailserver();
        $this->assertEquals('false', $result);

        //test with test and force true
        $result = $inboundEmail->connectMailserver(true, true);
        $this->assertEquals("Can't open mailbox {:/service=}: invalid remote specification<p><p><p>", $result);
    }

    public function testcheckImap()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->checkImap();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testget_summary_text()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting name
        $this->assertEquals(null, $inboundEmail->get_summary_text());

        //test with name set
        $inboundEmail->name = 'test';
        $this->assertEquals('test', $inboundEmail->get_summary_text());
    }

    public function testcreate_export_query()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test with empty string params
        $expected = " SELECT  inbound_email.*  , jt0.user_name created_by_name , jt0.created_by created_by_name_owner  , 'Users' created_by_name_mod FROM inbound_email   LEFT JOIN  users jt0 ON jt0.id=inbound_email.created_by AND jt0.deleted=0\n AND jt0.deleted=0 where inbound_email.deleted=0";
        $actual = $inboundEmail->create_export_query('', '');
        $this->assertSame($expected, $actual);

        //test with valid string params
        $expected = " SELECT  inbound_email.*  , jt0.user_name created_by_name , jt0.created_by created_by_name_owner  , 'Users' created_by_name_mod FROM inbound_email   LEFT JOIN  users jt0 ON jt0.id=inbound_email.created_by AND jt0.deleted=0\n AND jt0.deleted=0 where (jt0.user_name=\"\") AND inbound_email.deleted=0 ORDER BY inbound_email.id";
        $actual = $inboundEmail->create_export_query('id', 'jt0.user_name=""');
        $this->assertSame($expected, $actual);
    }

    public function testget_list_view_data()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox_type = 'INBOX';
        $inboundEmail->status = 'Active';

        $result = $inboundEmail->get_list_view_data();

        $expected = array(
                        'DELETED' => '0',
                        'STATUS' => null,
                        'DELETE_SEEN' => '0',
                        'MAILBOX_TYPE' => 'INBOX',
                        'IS_PERSONAL' => '0',
                        'MAILBOX_TYPE_NAME' => null,
                        'GLOBAL_PERSONAL_STRING' => null,
                    );

        $this->assertTrue(is_array($result));
        $this->assertEquals($expected, $result);

        $result = $inboundEmail->get_list_view_data();
    }

    public function testfill_in_additional_list_fields()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->service = 'tls::ca::ssl::protocol';

        $result = $inboundEmail->fill_in_additional_list_fields();

        $this->assertEquals($inboundEmail->tls, 'tls');
        $this->assertEquals($inboundEmail->ca, 'ca');
        $this->assertEquals($inboundEmail->ssl, 'ssl');
        $this->assertEquals($inboundEmail->protocol, 'protocol');
    }

    public function testfill_in_additional_detail_fields()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->service = 'tls::ca::ssl::protocol';

        $result = $inboundEmail->fill_in_additional_detail_fields();

        $this->assertEquals($inboundEmail->tls, 'tls');
        $this->assertEquals($inboundEmail->ca, 'ca');
        $this->assertEquals($inboundEmail->ssl, 'ssl');
        $this->assertEquals($inboundEmail->protocol, 'protocol');
    }

    public function testisAutoImport()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $user = BeanFactory::newBean('Users');

        //test with invalid user
        $result = $inboundEmail->isAutoImport($user);
        $this->assertEquals(false, $result);

        //test with default user
        $user->retrieve('1');
        $result = $inboundEmail->isAutoImport($user);
        $this->assertEquals(false, $result);
    }

    public function testcleanOutCache()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->cleanOutCache();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testcopyEmails()
    {
//        $this->markTestIncomplete('Propably an error level changed in the code?');
//
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->id = 1;
//
//        // Execute the method and test that it works and doesn't throw an exception.
//        try {
//            $result = $inboundEmail->copyEmails(1, 'INBOX', 1, 'TRASH', array(1));
//            $this->assertTrue(true);
//        } catch (Exception $e) {
//            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
//        }
    }

    public function testmoveEmails()
    {
//        $this->markTestIncomplete('Propably an error level changed in the code?');
//
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->id = 1;
//
//        $result = $inboundEmail->moveEmails(1, 'INBOX', 1, 'TRASH', array(1));
//        $this->assertEquals(false, $result);
//
//        $result = $inboundEmail->moveEmails(1, 'INBOX', 2, 'TRASH', array(1));
//        $this->assertEquals(false, $result);
    }

    public function testgetTempFilename()
    {
//        $this->markTestIncomplete('Propably an error level changed in the code?');
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $inboundEmail->compoundMessageId = 'cmid';
//
//        //test with default false
//        $result = $inboundEmail->getTempFilename();
//        $this->assertEquals('cmid0', $result);
//
//        //test with true
//        $result = $inboundEmail->getTempFilename(true);
//        $this->assertEquals('cmid', $result);
    }

    public function testdeleteMessageOnMailServer()
    {
//        $this->markTestIncomplete('Deprecated way to check imap');
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $result = $inboundEmail->deleteMessageOnMailServer('1');
//
//        $this->assertEquals(false, $result);
    }

    public function testdeleteMessageOnMailServerForPop3()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->deleteMessageOnMailServerForPop3('1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testisPop3Protocol()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        //test without setting protocol
        $this->assertEquals(false, $inboundEmail->isPop3Protocol());

        //test with pop3 protocol
        $inboundEmail->protocol = 'pop3';
        $this->assertEquals(true, $inboundEmail->isPop3Protocol());
    }

    public function testSetAndGetUsersDefaultOutboundServerId()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $user = BeanFactory::newBean('Users');
        $user->retrieve(1);

        //set a Outbound Server Id
        $inboundEmail->setUsersDefaultOutboundServerId($user, '11111111-1111-1111-1111-111111111111');

        //retrieve Outbound Server Id back and verify
        $result = $inboundEmail->getUsersDefaultOutboundServerId($user);
        $isValidator = new SuiteCRM\Utility\SuiteValidator();

        $this->assertTrue($isValidator->isValidId($result));
    }

    public function testsetEmailForDisplay()
    {
//        $this->markTestIncomplete('Deprecated pop3 test');
//
//        $inboundEmail = BeanFactory::newBean('InboundEmail');
//
//        $result = $inboundEmail->setEmailForDisplay('');
//        $this->assertEquals('NOOP', $result);
//
//        //test with pop3 protocol and default parameters
//        $inboundEmail->protocol = 'pop3';
//        $result = $inboundEmail->setEmailForDisplay('1');
//        $this->assertEquals('error', $result);
//
//        //test with pop3 protocol and all parameters true
//        $result = $inboundEmail->setEmailForDisplay('1', true, true, true);
//        $this->assertEquals('error', $result);
    }

    public function testdisplayOneEmail()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;
        $inboundEmail->mailbox = 'INBOX';
        $inboundEmail->email = BeanFactory::newBean('Emails');

        $inboundEmail->email->name = 'test';
        $inboundEmail->email->from_addr_name = 'from';
        $inboundEmail->email->to_addrs_names = 'to';
        $inboundEmail->email->cc_addrs_names = 'cc';
        $inboundEmail->email->reply_to_addr = 'reply';

        $expected = array(
                          'meta' => array('type' => 'archived', 'uid' => 1, 'ieId' => 1, 'email' => array('name' => 'test', 'from_name' => '', 'from_addr' => 'from', 'date_start' => ' ', 'time_start' => '', 'message_id' => '', 'cc_addrs' => 'cc', 'attachments' => '', 'toaddrs' => 'to', 'description' => '', 'reply_to_addr' => 'reply'), 'mbox' => 'INBOX', 'cc' => '', 'is_sugarEmail' => false),
                        );
        $result = $inboundEmail->displayOneEmail(1, 'INBOX');

        $this->assertEquals($expected, $result);
    }

    public function testcollapseLongMailingList()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $emails = 'one@email.com,two@email.com,three@email.com,four@email.com,five@email.com,six@email.com';

        $expected = "<span onclick='javascript:SUGAR.email2.detailView.showFullEmailList(this);' style='cursor:pointer;'>one@email.com, two@email.com [...4 More]</span><span onclick='javascript:SUGAR.email2.detailView.showCroppedEmailList(this)' style='cursor:pointer; display:none;'>one@email.com, two@email.com, three@email.com, four@email.com, five@email.com, six@email.com [ less ]</span>";

        $actual = $inboundEmail->collapseLongMailingList($emails);

        $this->assertEquals($expected, $actual);
    }

    public function testsortFetchedOverview()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = 1;
        $inboundEmail->mailbox = 'INBOX';

        $overview1 = new Overview();
        $overview1->subject = 'subject 1';
        $overview1->from = 'from 1';
        $overview1->flagged = '1';
        $overview1->answered = '1';
        $overview1->date = '2016-01-01';

        $overview2 = new Overview();
        $overview2->subject = 'subject 2';
        $overview2->from = 'from 2';
        $overview2->flagged = '2';
        $overview2->answered = '2';
        $overview2->date = '2016-01-02';

        $arr = array();
        $arr[] = $overview1;
        $arr[] = $overview2;

        //execute the method to sort the objects array descending and verify the order
        $result = $inboundEmail->sortFetchedOverview($arr, 3, 'DESC');
        $this->assertEquals('subject 2', $result['retArr'][0]->subject);

        //execute the method to sort the objects array ascending and verify the order
        $result = $inboundEmail->sortFetchedOverview($arr, 3, 'ASC');
        $this->assertEquals('subject 1', $result['retArr'][0]->subject);
    }

    public function testdisplayFolderContents()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $expected = array('mbox' => 'INBOX', 'ieId' => 1, 'name' => 'test', 'fromCache' => 0, 'out' => array());
        $inboundEmail->id = 1;
        $inboundEmail->name = 'test';

        $result = $inboundEmail->displayFolderContents('INBOX', 'false', 1);

        $this->assertEquals($expected, $result);
    }

    public function testcreateUserSubscriptionsForGroupAccount()
    {
//        $this->markTestIncomplete("Fatal error: Class 'Team' not found");
//
////
////
////        //unset and reconnect Db to resolve mysqli fetch exeception
////        $db = DBManagerFactory::getInstance();
////        unset($db->database);
////        $db->checkConnection();
////
////        $inboundEmail = BeanFactory::newBean('InboundEmail');
////
////        //$inboundEmail->createUserSubscriptionsForGroupAccount();
////
////
    }

    public function testcreateAutoImportSugarFolder()
    {
        //unset and reconnect Db to resolve mysqli fetch exeception
        $db = DBManagerFactory::getInstance();
        unset($db->database);
        $db->checkConnection();

        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->name = 'test';

        $result = $inboundEmail->createAutoImportSugarFolder();

        //test for record ID to verify that record is saved
        $this->assertTrue(isset($result));
        $this->assertEquals(36, strlen($result));
    }

    public function testgetMailboxes()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailboxarray = array('INBOX.TRASH', 'OUTBOX.TRASH');
        $expected = array('INBOX' => array('TRASH' => 'TRASH'), 'OUTBOX' => array('TRASH' => 'TRASH'));

        //test with justRaw default/false
        $result = $inboundEmail->getMailboxes();
        $this->assertEquals($expected, $result);

        //test with justRaw true
        $result = $inboundEmail->getMailboxes(true);
        $this->assertEquals($inboundEmail->mailboxarray, $result);
    }

    public function testgetMailBoxesForGroupAccount()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 1;
        $inboundEmail->mailbox = 'INBOX.TRASH,OUTBOX.TRASH';
        $expected = array('INBOX' => array('TRASH' => 'TRASH'), 'OUTBOX' => array('TRASH' => 'TRASH'));

        $result = $inboundEmail->getMailBoxesForGroupAccount();

        $this->assertEquals($expected, $result);
    }

    public function testretrieveMailBoxFolders()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->mailbox = 'INBOX,OUTBOX,TRASH';

        $inboundEmail->retrieveMailBoxFolders();

        $this->assertEquals(array('INBOX', 'OUTBOX', 'TRASH'), $inboundEmail->mailboxarray);
    }

    public function testinsertMailBoxFolders()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->id = '101';

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->insertMailBoxFolders(array('INBOX', 'OUTBOX'));
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testretrieveDelimiter()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->retrieveDelimiter();

        $this->assertEquals('.', $result);
    }

    public function testgenerateFlatArrayFromMultiDimArray()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $arraymbox = array('INBOX' => array('TRASH' => 'TRASH'), 'OUTBOX' => array('TRASH' => 'TRASH'));

        $expected = array('INBOX', 'INBOX.TRASH', 'OUTBOX', 'OUTBOX.TRASH');

        $result = $inboundEmail->generateFlatArrayFromMultiDimArray($arraymbox, '.');

        $this->assertEquals($expected, $result);
    }

    public function testgenerateMultiDimArrayFromFlatArray()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $expected = array('INBOX' => array('TRASH' => 'TRASH'), 'OUTBOX' => array('TRASH' => 'TRASH'));

        $result = $inboundEmail->generateMultiDimArrayFromFlatArray(array('INBOX.TRASH', 'OUTBOX.TRASH'), '.');

        $this->assertEquals($expected, $result);
    }

    public function testgenerateArrayData()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = array();
        $arraymbox = array('INBOX' => array('TRASH' => 'TRASH'));
        $expected = array('MAIN', 'MAIN.INBOX', 'MAIN.INBOX.TRASH');

        $inboundEmail->generateArrayData('MAIN', $arraymbox, $result, '.');

        $this->assertEquals($expected, $result);
    }

    public function testsortMailboxes()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $result = $inboundEmail->sortMailboxes('INBOX.TRASH', array());

        $expected = array('INBOX' => array('TRASH' => 'TRASH'));

        $this->assertEquals($expected, $result);
    }

    public function testgetServiceString()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        $inboundEmail->service = 'tls::ca::ssl::protocol';

        $result = $inboundEmail->getServiceString();

        $this->assertEquals('/tls/ca/ssl/protocol', $result);
    }

    public function testgetNewEmailsForSyncedMailbox()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->getNewEmailsForSyncedMailbox();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testimportMessages()
    {
        $inboundEmail = BeanFactory::newBean('InboundEmail');

        // Execute the method and test that it works and doesn't throw an exception.
        try {
            $inboundEmail->protocol = 'pop3';
            $inboundEmail->importMessages();

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("\nException: " . get_class($e) . ": " . $e->getMessage() . "\nin " . $e->getFile() . ':' . $e->getLine() . "\nTrace:\n" . $e->getTraceAsString() . "\n");
        }
    }

    public function testOverview()
    {
//        $this->markTestIncomplete('Fatal error: Class \'Overview\' not found');
////
////
////        // Execute the constructor and check for the Object type and  attributes
////        $overview = new Overview();
////
////        $this->assertInstanceOf('Overview', $overview);
////
////        $this->assertTrue(is_array($overview->fieldDefs));
////        $this->assertTrue(is_array($overview->indices));
    }
}
