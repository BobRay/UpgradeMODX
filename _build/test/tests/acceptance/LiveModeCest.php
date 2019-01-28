<?php

use Page\Ovariables as oPageVariables;
use Page\Login as LoginPage;
use \Helper\Acceptance;

class LiveModeCest {
    /** @var $modx modX */
    public $modx = null;
    public $devModeValue;

    public function _before(AcceptanceTester $I) {
        ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        if (!class_exists('modX')) {
            require \Page\ugmVariables::$configPath;
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
        }
        $this->devModeValue = $setting->get('value');
        if ((bool)$setting->get('value') === true) {
            $this->_setSystemSetting('ugm.devMode', '0');
        }

        $this->_setSystemSetting('settings_version', '2.6.4-pl');

    }

    /**
     * @param $modx modX
     */
    /*public function _clearSettingCache() {
        $cm = $this->modx->getCacheManager();
        $cm->refresh(array('system_settings' => array()));
    }*/

    /**
     * @param $modx modX
     * @param $key string
     * @param $value string
     */
    public function _setSystemSetting($key, $value) {
        $setting = $this->modx->getObject('modSystemSetting', array('key' => $key));
        if ($setting) {
            $setting->set('value', $value);
            $setting->save();
            $cm = $this->modx->getCacheManager();
            $cm->refresh(array('system_settings' => array()));
        }
    }

    // tests

    /** @param $I \AcceptanceTester
    *   @throws Exception
    */
    public function tryToTest(AcceptanceTester $I) {
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
        $I->clickWithLeftButton($element);
        $attr = $I->grabAttributeFrom('#2.7.0-pl', 'checked');
        $I->assertTrue($attr === "true");

/*        $element = '#2.6.5-pl';
        $I->scrollTo($element);

        $I->click('#2.6.5-pl');*/

        $element = '#ugm_submit_button';
        $I->clickWithLeftButton($element);

        // $I->wait(1);
        $I->waitForText('Downloading Files', 30, $element);
        $I->waitForText('Unzipping Files', 30, $element);
        $I->waitForText('Copying Files', 30, $element);
        $I->waitForText('Preparing Setup', 30, $element);
        $I->waitForText('Cleaning Up', 30, $element);
        $I->waitForText('Launching Setup', 30, $element);
       // $I->waitForText('Congratulations', 10);
        $I->waitForText('Choose Language', 10);
        $I->clickWithLeftButton('input[name="proceed"]');
        $I->waitForText('Welcome to', 3);
        $I->clickWithLeftButton('input[name="proceed"]');
        $I->waitForText('Install Options', 3);
        /* Update rather than new install */
        $I->clickWithLeftButton('#installmode1');
        $I->clickWithLeftButton('input[name="proceed"]');
        $I->waitForText('Installation Summary', 10);
        $I->clickWithLeftButton('#modx-next');
        $I->waitForText('Installation Summary', 10);
        $I->clickWithLeftButton('#modx-next');
        $I->waitForText('Thank you for installing', 15);
        $I->clickWithLeftButton('#modx-next');
        $loginPage->login();
        $I->waitForText('MODX Revolution 2.7.0-pl');
        $I->wait(5);
    }

    public function _after(AcceptanceTester $I) {
        $setting = $this->modx->getObject('ugm.devMode', null);
        if ($setting) {
            $this->_setSystemSetting('ugm.devMode', $this->devModeValue);
        }
    }
}
