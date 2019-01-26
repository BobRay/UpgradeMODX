<?php

use Page\Ovariables as oPageVariables;
use Page\Login as LoginPage;
use \Helper\Acceptance;

class UpgradeMODXCest
{
    /** @var $modx modX */
    public $modx = null;
    public $devModeValue;

    public function _before(AcceptanceTester $I)
    {
        ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        if (!class_exists('modX')) {
            require "c:/xampp/htdocs/test/core/config/config.inc.php";
            require MODX_CORE_PATH . 'model/modx/modx.class.php';
        }

        $this->modx = new modX();

        $modx =& $this->modx;
        $modx->initialize('mgr');

        $user = $modx->getObject('modUser', array('username' => 'JoeTester'));
        if (!$user) {
            $fields = array(
                'username' => 'JoeTester',
                'password' => 'testerPassword',
                'specifiedpassword' => 'testerPassword',
                'confirmpassword' => 'testerPassword',
                'email' => 'bobray99@gmail.com',
                'passwordnotifymethod' => 's',
                'passwordgenmethod' => 'x',
                'active' => '1',
            );

            $modx->runProcessor('security/user/create', $fields);
            $user = $modx->getObject('modUser', array('username' => 'JoeTester'));
            /** @var $user modUser */
            $user->joinGroup('Administrator', 2);
        }
        $modx->user =& $user;

        $setting = $modx->getObject('modSystemSetting', array('key' => 'ugm.devMode'));
        if (!$setting) {
            $setting = $modx->newObject('modSystemSetting');
            $setting->set('key', 'ugm.devMode');
            // $setting->set('name', 'UpgradeMODX dev mode');
            $setting->set('namespace', 'core');
            $setting->set('value', '1');
            if (!$setting->save()) {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not create devMode Setting');
            }
        } else {
            $this->devModeValue = $setting->get('value');
            if (! (bool) $setting->get('value') === 1) {
                $setting->set('value', '1');
                $setting->save();
                $cm = $modx->getCacheManager();
                $cm->refresh(array('system_settings' => array()));
            }
        }
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        /** @var $I AcceptanceTester */
        /* *************** */
        $I->wantTo('Log In');
        $loginPage = new LoginPage($I);
        $loginPage->login();
        $I->amOnPage('manager');
        $I->see('Upgrade Available');
        $element = '#2.7.0-pl';
        $I->scrollTo($element);
        $I->see('(current version)');
        $element = '#2.6.5-pl';
        $I->scrollTo($element);

        $I->click('#2.6.5-pl');
        $attr = $I->grabAttributeFrom('#2.6.5-pl', 'checked');
        $I->assertTrue($attr === "true");
        /*if ($attr == 'checked') {
            $I->click('XXX');
        }*/
        $element = '#ugm_submit_button';
       //  $I->scrollTo($element);
       //  $I->waitForElementVisible($element);
        $I->clickWithLeftButton($element);

       //  $element = 'span#button_content.content';
       /* $I->waitForElement('//span[@id="button_content" and text()="Downloading Files"]', 10);
        $I->waitForElement('//span[@id="button_content" and text()="Unzipping Files"]', 10);
        $I->waitForElement('//span[@id="button_content" and text()="Copying Files"]', 10);*/
        // $I->waitForElement('//[@id="element_id"][text()="Copying Files"]', 30);

        // $I->wait(1);
        $I->waitForText('Downloading Files', 2, $element);
        $I->waitForText('Unzipping Files', 30, $element);
        $I->waitForText('Copying Files', 30, $element);
        $I->waitForText('Preparing Setup', 30, $element);
        $I->waitForText('Cleaning Up', 30, $element);
        $I->waitForText('Launching Setup',30, $element);
        $I->waitForText('Congratulations',10);
        $I->wait(7);

    }

    public function _after(AcceptanceTester $I) {
        $setting = $this->modx->getObject('ugm.devMode', null);
        if ($setting) {
            $setting->set('value', $this->devModeValue);
        }
    }
}
