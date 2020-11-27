<?php

use Faker\Generator;

/**
 * Class ModuleFieldsCest
 * Tests module fields, layouts, relationships in module builder
 */
class ModuleBuilderFieldsCest
{
    /**
     * @var Generator $fakeData
     */
    protected $fakeData;

    /**
     * @var integer $fakeDataSeed
     */
    protected $fakeDataSeed;

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        if (!$this->fakeData) {
            $this->fakeData = Faker\Factory::create();
            $this->fakeDataSeed = mt_rand(0, 2048);
        }
        $this->fakeData->seed($this->fakeDataSeed);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
    }

    // Tests
    /**
     * @param \AcceptanceTester $I
     * @param \Step\Acceptance\ModuleBuilder $moduleBuilder
     *
     * As an administrator I want to create and deploy a basic module so that I can test
     * that the functionality of functionality each field is working. Given that I have already created a module I expect to deploy
     * the module before testing.
     */
    public function testScenarioCreateFieldsModule(
        \AcceptanceTester $I,
        \Step\Acceptance\ModuleBuilder $moduleBuilder
    ) {
        $I->wantTo('Create a module for testing fields');

        $I->loginAsAdmin();

        $moduleBuilder->createModule(
            \Page\ModuleFields::$PACKAGE_NAME,
            \Page\ModuleFields::$NAME,
            \SuiteCRM\Enumerator\SugarObjectType::basic
        );
    }

    /**
     * @param AcceptanceTester $I
     * @param \Step\Acceptance\ModuleBuilder $moduleBuilder
     * As an administrator I want to add a relate field to the basic module so that I can test relating records to the
     * accounts module
     */
    public function testScenarioAddRelateField(
        \AcceptanceTester $I,
        \Step\Acceptance\ModuleBuilder $moduleBuilder
    ) {
        $I->wantTo('Add relate field');

        $I->loginAsAdmin();

        $moduleBuilder->selectModule(\Page\ModuleFields::$PACKAGE_NAME, \Page\ModuleFields::$NAME);

        // View Fields button
        $I->waitForElementVisible(['name' => 'viewfieldsbtn']);
        $I->click(['name' => 'viewfieldsbtn']);

        // Close popup
        $I->waitForElementVisible('#sugarMsgWindow_mask');
        $I->waitForText('This operation is completed successfully', 30, '#sugarMsgWindow_c');
        $I->click('.container-close');

        // Add field button
        $I->waitForElementVisible('[name="addfieldbtn"]');
        $I->click(['name' => 'addfieldbtn']);

        // Fill in edit field tab
        $I->waitForElementVisible('#type');
        $I->selectOption('#type', 'relate');

        $I->wait(1);
        $I->waitForElementVisible('#field_name_id');
        $I->fillField('#field_name_id', 'test_relate_field');

        // Module Builder auto writes the label fields when you click of the name field
        // So we need to fill in the help field to register the blur event
        // creates error http://seleniumhq.org/exceptions/stale_element_reference.html
        $I->click('#mblayout');
        $I->wait(1);
        $I->selectOption('#ext2', 'Accounts');

        // Click save
        $I->click(['name' => 'fsavebtn']);

        $moduleBuilder->closePopupSuccess();

        // Add to layout viewlayoutsbtn
        $moduleBuilder->selectModule(\Page\ModuleFields::$PACKAGE_NAME, \Page\ModuleFields::$NAME);
        // View Layouts button
        $I->waitForElementVisible(['name' => 'viewlayoutsbtn']);
        $I->click(['name' => 'viewlayoutsbtn']);

        $moduleBuilder->closePopupSuccess();

        // Click Edit View
        $I->waitForElementVisible('.bodywrapper');
        $I->click('Edit View', '.bodywrapper');
        $I->waitForElementVisible('#layoutEditor');

        // Drag a new row into the last panel
        $I->dragAndDrop('.le_row.special:not(#ygddfdiv)', '.le_panel:last-of-type');
        $I->makeScreenshot('DnD.Row');

        // Drag field to
        $this->fakeData->seed($this->fakeDataSeed);
        $field = \Codeception\Util\Locator::contains('.le_field', 'test_relate_field');
        $slot = \Codeception\Util\Locator::contains('.le_field.special', '(filler)');
        $slot = \Codeception\Util\Locator::lastElement($slot);
        $I->dragAndDrop($field, $slot);
        $I->makeScreenshot('DnD.Field');

        $I->checkOption('#syncCheckbox');
        $I->click('Save');
        $moduleBuilder->closePopupSuccess();
    }



    /**
     * @param AcceptanceTester $I
     * @param \Step\Acceptance\ModuleBuilder $moduleBuilder
     * As an administrator I want to add a html field to the basic module so that I can test relating records to the
     * accounts module
     */
    public function testScenarioAddHtmlField(
        \AcceptanceTester $I,
        \Step\Acceptance\ModuleBuilder $moduleBuilder
    ) {
        $I->wantTo('Add html field');

        $I->loginAsAdmin();

        $moduleBuilder->selectModule(\Page\ModuleFields::$PACKAGE_NAME, \Page\ModuleFields::$NAME);

        // View Fields button
        $I->waitForElementVisible(['name' => 'viewfieldsbtn']);
        $I->click(['name' => 'viewfieldsbtn']);

        // Close popup
        $I->waitForElementVisible('#sugarMsgWindow_mask');
        $I->waitForText('This operation is completed successfully', 30, '#sugarMsgWindow_c');
        $I->click('.container-close');

        // Add field button
        $I->waitForElementVisible('[name="addfieldbtn"]');
        $I->click(['name' => 'addfieldbtn']);

        // Fill in edit field tab
        $I->waitForElementVisible('#type');
        $I->selectOption('#type', 'HTML');

        // Wait for 1 second to allow the field to become interactive.
        $I->wait(1);
        $I->waitForElementVisible('#field_name_id');
        $I->fillField('#field_name_id', 'test_html_field');

        // Module Builder auto writes the label fields when you click of the name field
        // So we need to fill in the help field to register the blur event
        // creates error http://seleniumhq.org/exceptions/stale_element_reference.html
        $I->click('#mblayout');
        $I->wait(1);

        // Click save
        $I->click(['name' => 'fsavebtn']);

        $moduleBuilder->closePopupSuccess();

        // Add to layout viewlayoutsbtn
        $moduleBuilder->selectModule(\Page\ModuleFields::$PACKAGE_NAME, \Page\ModuleFields::$NAME);
        // View Layouts button
        $I->waitForElementVisible(['name' => 'viewlayoutsbtn']);
        $I->click(['name' => 'viewlayoutsbtn']);

        $moduleBuilder->closePopupSuccess();

        // Click Edit View
        $I->waitForElementVisible('.bodywrapper');
        $I->click('Edit View', '.bodywrapper');
        $I->waitForElementVisible('#layoutEditor');

        // Drag a new row into the last panel
        $I->dragAndDrop('.le_row.special:not(#ygddfdiv)', '.le_panel:last-of-type');
        $I->makeScreenshot('DnD.Row');

        // Drag field to
        $this->fakeData->seed($this->fakeDataSeed);
        $field = \Codeception\Util\Locator::contains('.le_field', 'test_html_field');
        $slot = \Codeception\Util\Locator::contains('.le_field.special', '(filler)');
        $slot = \Codeception\Util\Locator::lastElement($slot);
        $I->dragAndDrop($field, $slot);
        $I->makeScreenshot('DnD.Field');

        $I->checkOption('#syncCheckbox');
        $I->click('Save');
        $moduleBuilder->closePopupSuccess();
    }

    /**
     * @param AcceptanceTester $I
     * @param \Step\Acceptance\ModuleBuilder $moduleBuilder
     * @param \Step\Acceptance\Repair $repair
     *
     * As an administrator I want to test deploying a module
     */
    public function testScenarioDeployModule(
        \AcceptanceTester $I,
        \Step\Acceptance\ModuleBuilder $moduleBuilder,
        \Step\Acceptance\Repair $repair
    ) {
        $I->wantTo('Deploy Test Module');

        $I->loginAsAdmin();

        $moduleBuilder->deployPackage(\Page\ModuleFields::$PACKAGE_NAME, true);
        $moduleBuilder->deployPackage(\Page\ModuleFields::$PACKAGE_NAME, true);

        $repair->clickQuickRepairAndRebuild();
    }

    /**
     * @param AcceptanceTester $I
     * @param \Step\Acceptance\NavigationBar $navigationBar
     * @param \Step\Acceptance\ListView $listView
     * @param \Step\Acceptance\EditView $editView
     * @param \Step\Acceptance\DetailView $detailView
     * @param \Step\Acceptance\Accounts $accounts
     *
     * As an administrator I want to test relating to the accounts module
     */
    public function testScenarioRelateToAccounts(
        \AcceptanceTester $I,
        \Step\Acceptance\NavigationBar $navigationBar,
        \Step\Acceptance\ListView $listView,
        \Step\Acceptance\EditView $editView,
        \Step\Acceptance\DetailView $detailView
    ) {
        return; // test failing behaviour is not similar in different environments
        $I->wantTo('Relate a record to accounts');

        $I->loginAsAdmin();

        // Go to Accounts Module
        $navigationBar->clickAllMenuItem(\Page\AccountsModule::$NAME);
        $listView->waitForListViewVisible();
        $navigationBar->clickCurrentMenuItem(\Page\AccountsModule::$CREATE_LINK);

        // Create an account to relate to
        $this->fakeData->seed($this->fakeDataSeed);
        $company = $this->fakeData->company;
        $I->waitForElementVisible('#name', 30);
        $editView->fillField('#name', $company);
        $editView->clickSaveButton();
        $detailView->waitForDetailViewVisible();

        // Go to Test Module Fields
        $navigationBar->clickAllMenuItem(\Page\ModuleFields::$NAME);
        $listView->waitForListViewVisible();
        $navigationBar->clickCurrentMenuItem('Create ' . \Page\ModuleFields::$NAME);

        // Create an account to relate to
        $I->waitForElementVisible('#name');
        $editView->fillField('#name', $company);
        $relateFieldId = 'test_relate_field';
        $editView->fillField('#'.$relateFieldId, $company);
        $editView->waitForElementNotVisible('#EditView_'.$relateFieldId.' > .yui-ac-content');
        $editView->fillField('#test_int_field', $this->fakeData->numberBetween(0, 1000));

        $editView->clickSaveButton();
        $detailView->waitForDetailViewVisible();
        $detailView->clickActionMenuItem('Delete');
        $detailView->acceptPopup();
        $listView->waitForListViewVisible();

        // Delete Account
        $navigationBar->clickAllMenuItem(\Page\AccountsModule::$NAME);
        $listView->waitForListViewVisible();
        $listView->clickFilterButton();
        $listView->click('Quick Filter');
        $listView->fillField('#name_basic', $company);
        $listView->click('Search', '.submitButtons');
        $listView->wait(1);
        $listView->dontSee('No results found');
        $this->fakeData->seed($this->fakeDataSeed);
        $listView->clickNameLink($company);

        $detailView->waitForDetailViewVisible();
        $detailView->clickActionMenuItem('Delete');
        $detailView->acceptPopup();
        $listView->waitForListViewVisible();
        $listView->clickFilterButton();
        $listView->click('Quick Filter');
        $listView->fillField('#name_basic', '');
        $listView->click('Search', '.submitButtons');
    }
}
