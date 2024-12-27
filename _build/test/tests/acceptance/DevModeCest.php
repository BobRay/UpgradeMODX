<?php

use Page\Login as LoginPage;
use Page\Ovariables as oPageVariables;
use tests\_support\AcceptanceTester;


class DevModeCest
{
    /** @var $modx modX */
    public $modx = null;
    public $devModeValue;

    public function _before(AcceptanceTester $I)
    {
        ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        if (!class_exists('modX')) {
            require \Page\ugmVariables::$configPath;
            require MODX_CORE_PATH . 'model/modx/modx.class.php';
        }


        $this->modx = new modX();
        $modx =& $this->modx;
        $modx->initialize('mgr');

        $user = $modx->getObject('modUser', array('username' => 'JoeTester'));
        if ($user) {
            $user->remove();
        }

        $I->wantTo('Create Test User');
        $fields = array(
            'username' => 'JoeTester',
            'password' => 'TesterPassword',
            'specifiedpassword' => 'TesterPassword',
            'confirmpassword' => 'TesterPassword',
            'email' => 'bobray99@gmail.com',
            'passwordnotifymethod' => 's',
            'passwordgenmethod' => 'x',
            'active' => '1',
        );

        $modx->runProcessor('security/user/create', $fields);
        $user = $modx->getObject('modUser', array('username' => 'JoeTester'));
        /** @var $user modUser */
        $user->joinGroup('Administrator', 2);

        $modx->user =& $user;

        $setting = $modx->getObject('modSystemSetting', array('key' => 'settings_version'));

        if ($setting) {
            $setting->set('value', '2.7.0-pl');
            $setting->save();
        } else {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not get settings_version System Setting');
        }

        $setting = $modx->getObject('modSystemSetting', array('key' => 'ugm.devMode'));
        if (!$setting) {
            $I->wantTo('Create Dev Mode Setting');
            $setting = $modx->newObject('modSystemSetting');
            $setting->set('key', 'ugm.devMode');
            // $setting->set('name', 'UpgradeMODX dev mode');
            $setting->set('namespace', 'core');
            $setting->set('value', '1');

            if (!$setting->save()) {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not create devMode Setting');
            }

            $this->_clearSettingCache();
        } else {
            $this->devModeValue = $setting->get('value');
            if (! (bool) ($setting->get('value') === 1)) {
                $setting->set('value', '1');
                if (!$setting->save()) {
                   $modx->log(modX::LOG_LEVEL_ERROR, 'Could not save ugm.devMode System Setting');
                }
                $this->_clearSettingCache();
            }
        }
    }

    /**
     * @param $modx modX
     */
    public function _clearSettingCache() {
        $cm = $this->modx->getCacheManager();
        $cm->refresh(array('system_settings' => array()));
    }

    /**
     * @param $modx modX
     * @param $key string
     * @param $value string
     */
   /* public function _setSystemSetting ($key, $value) {
        $setting = $this->modx->getObject('modSystemSetting', array('key' => $key));
        if ($setting) {
            $setting->set('value', $value);
            $setting->save();
        }
    }*/

    // tests
    /** @throws Exception */
    public function tryToTestDevMode(AcceptanceTester $I)
    {
        /** @var $I AcceptanceTester */
        /* *************** */

        $loginPage = new LoginPage($I);
        $loginPage->login();
        $I->amOnPage('manager');
        $I->dontSee('Run in Dev Mode');
        $I->see('Upgrade Available');
        $element = '#2.7.0-pl';
        $I->scrollTo($element);
        $I->see('(current version)');
        $element = '#2.8.0-pl';
        $I->scrollTo($element);
        $I->click('#2.8.0-pl');
        $attr = $I->grabAttributeFrom('#2.8.0-pl', 'checked');
        $I->assertTrue($attr === "true");
        $element = '#ugm_submit_button';
        $I->scrollTo($element);
        $I->clickWithLeftButton($element);

        // $I->wait(1);
        $I->waitForText('Downloading Files', 2, $element);
        $I->wait(1);
        $I->waitForText('Unzipping Files', 30, $element);
        $I->waitForText('Copying Files', 30, $element);
        $I->waitForText('Preparing Setup', 30, $element);
        $I->waitForText('Cleaning Up', 30, $element);
        $I->waitForText('Launching Setup',30, $element);
        $I->waitForText('Congratulations',10);
        $I->wait(2);

    }

    public function _after(AcceptanceTester $I) {
        $setting = $this->modx->getObject('ugm.devMode', null);
        if ($setting) {
            $setting->set('value', $this->devModeValue);
           // $setting->save();
            $this->_clearSettingCache();
        }
    }
}
