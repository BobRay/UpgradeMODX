<?php
/**
 * systemSettings transport file for UpgradeMODX extra
 *
 * Copyright 2015-2018 by Bob Ray <https://bobsguides.com>
 * Created on 08-01-2018
 *
 * @package upgrademodx
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $systemSettings */


$systemSettings = array();

$systemSettings[1] = $modx->newObject('modSystemSetting');
$systemSettings[1]->fromArray(array (
  'key' => 'ugm.versionListPath',
  'value' => '{core_path}cache/upgrademodx/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'versionListPath',
  'description' => 'Path to versionlist file (minus the filename -- should end in a slash); Default: {core_path}cache/upgrademodx/',
), '', true, true);
$systemSettings[2] = $modx->newObject('modSystemSetting');
$systemSettings[2]->fromArray(array (
  'key' => 'ugm.lastCheck',
  'value' => '2018-08-09 18:05:58',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'lastCheck',
  'description' => 'Date and time of last check -- set automatically',
), '', true, true);
$systemSettings[3] = $modx->newObject('modSystemSetting');
$systemSettings[3]->fromArray(array (
  'key' => 'ugm.latestVersion',
  'value' => '2.6.5-pl',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'latestVersion',
  'description' => 'Latest version (at last check) -- set automatically',
), '', true, true);
$systemSettings[4] = $modx->newObject('modSystemSetting');
$systemSettings[4]->fromArray(array (
  'key' => 'ugm.hideWhenNoUpgrade',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'hideWhenNoUpgrade',
  'description' => 'Hide widget when no upgrade is available: default: No',
), '', true, true);
$systemSettings[5] = $modx->newObject('modSystemSetting');
$systemSettings[5]->fromArray(array (
  'key' => 'ugm.interval',
  'value' => '1 day',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'interval',
  'description' => 'Interval between checks -- Examples: 1 week, 3 days, 6 hours; default: 1 day',
), '', true, true);
$systemSettings[6] = $modx->newObject('modSystemSetting');
$systemSettings[6]->fromArray(array (
  'key' => 'ugm.groups',
  'value' => 'Administrator',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Security',
  'name' => 'groups',
  'description' => 'group, or comma-separated list of groups, who will see the widget',
), '', true, true);
$systemSettings[7] = $modx->newObject('modSystemSetting');
$systemSettings[7]->fromArray(array (
  'key' => 'ugm.versionsToShow',
  'value' => '5',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'versionsToShow',
  'description' => 'Number of versions to show in upgrade form; default: 5',
), '', true, true);
$systemSettings[8] = $modx->newObject('modSystemSetting');
$systemSettings[8]->fromArray(array (
  'key' => 'ugm.githubTimeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'githubTimeout',
  'description' => 'Timeout in seconds for checking Github; default: 6',
), '', true, true);
$systemSettings[9] = $modx->newObject('modSystemSetting');
$systemSettings[9]->fromArray(array (
  'key' => 'ugm.github_token',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'github_token',
  'description' => 'Github token - available from your GitHub profile',
), '', true, true);
$systemSettings[10] = $modx->newObject('modSystemSetting');
$systemSettings[10]->fromArray(array (
  'key' => 'ugm.github_username',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'github_username',
  'description' => 'Your username at GitHub',
), '', true, true);
$systemSettings[11] = $modx->newObject('modSystemSetting');
$systemSettings[11]->fromArray(array (
  'key' => 'ugm.plOnly',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'plOnly',
  'description' => 'Show only pl (stable) versions; default: yes',
), '', true, true);
$systemSettings[12] = $modx->newObject('modSystemSetting');
$systemSettings[12]->fromArray(array (
  'key' => 'ugm.language',
  'value' => 'en',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'language',
  'description' => 'Two-letter language code for language to use; default: en',
), '', true, true);
$systemSettings[13] = $modx->newObject('modSystemSetting');
$systemSettings[13]->fromArray(array (
  'key' => 'ugm.ssl_verify_peer',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'ssl_verify_peer',
  'description' => 'For security, have cURL verify the identity of the server',
), '', true, true);
$systemSettings[14] = $modx->newObject('modSystemSetting');
$systemSettings[14]->fromArray(array (
  'key' => 'ugm.modxTimeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'modxTimeout',
  'description' => 'Timeout in seconds for checking download status from MODX; default: 6',
), '', true, true);
$systemSettings[15] = $modx->newObject('modSystemSetting');
$systemSettings[15]->fromArray(array (
  'key' => 'ugm.forcePclZip',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'forcePclZip',
  'description' => 'Force the use of PclZip instead of ZipArchive',
), '', true, true);
$systemSettings[16] = $modx->newObject('modSystemSetting');
$systemSettings[16]->fromArray(array (
  'key' => 'ugm.attempts',
  'value' => '2',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'attempts',
  'description' => 'Number of tries to get data from GitHub or MODX; default: 2',
), '', true, true);
$systemSettings[17] = $modx->newObject('modSystemSetting');
$systemSettings[17]->fromArray(array (
  'key' => 'ugm.forceFopen',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'forceFopen',
  'description' => 'Force the use of fopen instead of cURL for the download',
), '', true, true);
$systemSettings[18] = $modx->newObject('modSystemSetting');
$systemSettings[18]->fromArray(array (
  'key' => 'ugm.VersionListApiURL',
  'value' => '//api.github.com/repos/modxcms/revolution/tags',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Version List API URL',
  'description' => 'URL of API to get version list from',
), '', true, true);
$systemSettings[19] = $modx->newObject('modSystemSetting');
$systemSettings[19]->fromArray(array (
  'key' => 'ugm_temp_dir',
  'value' => '{base_path}ugmtemp/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'UpgradeMODX Temp Directory',
  'description' => 'Path to the directory used for temporary storage for downloading and unzipping files; Must be writable; default:{base_path}ugmtemp/',
), '', true, true);
return $systemSettings;
