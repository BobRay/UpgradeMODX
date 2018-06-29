<?php
/**
 * Created by PhpStorm.
 * User: BobRay
 * Date: 6/28/2018
 * Time: 12:24 AM
 */

/**
 * Description
 * -----------
 * Create upgrade.php file
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 **/

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/core/');
    define('MODX_BASE_PATH', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/');
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');
}
if (!defined('MODX_BASE_URL')) {
    define('MODX_BASE_URL', 'http://localhost/addons/');
    define('MODX_MANAGER_URL', 'http://localhost/addons/manager/');
    define('MODX_ASSETS_URL', 'http://localhost/addons/assets/');
    define('MODX_CONNECTORS_URL', 'http://localhost/addons/connectors/');
}
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('mgr');
$modx->getService('error', 'error.modError', '', '');
$modx->lexicon->load('mycomponent:default');
$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);

$props = array(
    'plOnly' => true,
    'latestVersion' => '2.6.4-pl',
    'devMode' => true,
);

$corePath = $modx->getOption('ugm.core_path', $props, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/upgrademodx/');

require_once($corePath . 'model/upgrademodx.class.php');

$ugm = new UpgradeMODX($modx);



$ugm->init($props);
$ugm->writeScriptFile(true);

echo "Finished";