<?php

use Faker\Factory;
use Faker\Generator;
use Step\Acceptance\Accounts;
use Step\Acceptance\DetailView;
use Step\Acceptance\EditView;
use Step\Acceptance\ListView;
use Step\Acceptance\UsersTester;

class UsersCest
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
            $this->fakeData = Factory::create();
        }

        $this->fakeDataSeed = mt_rand(0, 2048);
        $this->fakeData->seed($this->fakeDataSeed);
    }
    
    public function testEmailSettingsMailAccountAdd(AcceptanceTester $I, UsersTester $Users)
    {
        $I->loginAsAdmin();
        $Users->gotoProfile();
        $I->see('User Profile', '.panel-heading');
        $I->click('Settings');
        $I->see('Mail Accounts');
        $I->click('Mail Accounts');
        $I->click('Add');
        $I->executeJS('javascript:SUGAR.email2.accounts.fillInboundGmailDefaults();'); // <-- instead of $I->click('Prefill Gmail™ Defaults');
        $I->fillField('ie_name', 'testuser_acc');
        $I->fillField('email_user', 'testuser_name');
        $I->fillField('email_password', 'testuser_pass');
        $I->click('Test Settings');
        $I->waitForText('Connection completed successfully.');
        $I->see('Connection completed successfully.');
    }

    public function testShowCollapsedSubpanelHint(
        AcceptanceTester $I,
        DetailView $DetailView,
        UsersTester $Users,
        ListView $listView,
        EditView $EditView,
        Accounts $accounts
    ) {
        $I->wantTo('View the collapsed subpanel hints on Accounts');

        // Navigate to Users list-view
        $I->loginAsAdmin();

        $Users->gotoProfile();

        $I->see('User Profile', '.panel-heading');

        $I->click("Layout Options");
        $I->waitForElementVisible('input[name="user_count_collapsed_subpanels"]');
        $I->seeElement('input', ['name' => 'user_count_collapsed_subpanels']);
        $I->checkOption(['name' => 'user_count_collapsed_subpanels']);
        $EditView->clickSaveButton();
        $DetailView->waitForDetailViewVisible();

        // Create & Navigate to Accounts
        // @TODO - Need to include dummy data to utilise these tests efficiently
        $I->wantTo('Create an Account');

        // Navigate to accounts list-view
        $I->visitPage('Accounts', 'index');
        $listView->waitForListViewVisible();

        // Create account
        $this->fakeData->seed($this->fakeDataSeed);
        $accountId = $accounts->createAccount('Test_'. $this->fakeData->company());

        $I->visitPage('Accounts', 'DetailView', $accountId);
        $DetailView->waitForDetailViewVisible();

        // View the Subpanels Hint
        $I->see('Leads (0)', '//*[@id="subpanel_title_leads"]/div/div');

        // Delete account
        $DetailView->clickActionMenuItem('Delete');
        $DetailView->acceptPopup();
        $listView->waitForListViewVisible();

        // Reset the collapsed subpanels
        $Users->gotoProfile();
        $I->see('User Profile', '.panel-heading');
        $I->click("Layout Options");
        $I->seeElement('input', ['name' => 'user_count_collapsed_subpanels']);
        $I->uncheckOption(['name' => 'user_count_collapsed_subpanels']);
        $EditView->clickSaveButton();
        $DetailView->waitForDetailViewVisible();
    }
}
