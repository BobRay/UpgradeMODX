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
  'key' => 'ugm_versionlist_api_url',
  'value' => '//api.github.com/repos/modxcms/revolution/tags',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Version List API URL',
  'description' => 'URL of API to get version list from',
), '', true, true);
$systemSettings[2] = $modx->newObject('modSystemSetting');
$systemSettings[2]->fromArray(array (
  'key' => 'ugm_temp_dir',
  'value' => '{base_path}ugmtemp/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'UpgradeMODX Temp Directory',
  'description' => 'Path to the directory used for temporary storage for downloading and unzipping files; Must be writable; default:{base_path}ugmtemp/',
), '', true, true);
$systemSettings[3] = $modx->newObject('modSystemSetting');
$systemSettings[3]->fromArray(array (
  'key' => 'ugm_cert_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'Cert Path',
  'description' => 'Path to SSL cert file in .pem format; rarely necessary',
), '', true, true);
$systemSettings[4] = $modx->newObject('modSystemSetting');
$systemSettings[4]->fromArray(array (
  'key' => 'ugm_file_version',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'File Version',
  'description' => 'Version when versionlist file was last updated. Set automatically -- do not edit!',
), '', true, true);
$systemSettings[5] = $modx->newObject('modSystemSetting');
$systemSettings[5]->fromArray(array (
  'key' => 'ugm_force_pcl_zip',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'Force PclZip',
  'description' => 'Force the use of PclZip instead of ZipArchive',
), '', true, true);
$systemSettings[6] = $modx->newObject('modSystemSetting');
$systemSettings[6]->fromArray(array (
  'key' => 'ugm_modx_timeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'MODX Timeout',
  'description' => 'Timeout in seconds for checking download status from MODX; default: 6',
), '', true, true);
$systemSettings[7] = $modx->newObject('modSystemSetting');
$systemSettings[7]->fromArray(array (
  'key' => 'ugm_ssl_verify_peer',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'SSL Verify Peer',
  'description' => 'For security, have cURL verify the identity of the server',
), '', true, true);
$systemSettings[8] = $modx->newObject('modSystemSetting');
$systemSettings[8]->fromArray(array (
  'key' => 'ugm_language',
  'value' => 'en',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'Language',
  'description' => 'Two-letter language code for language to use; default: en',
), '', true, true);
$systemSettings[9] = $modx->newObject('modSystemSetting');
$systemSettings[9]->fromArray(array (
  'key' => 'ugm_pl_only',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'pl Versions Only',
  'description' => 'Show only pl (stable) versions; default: yes',
), '', true, true);
$systemSettings[10] = $modx->newObject('modSystemSetting');
$systemSettings[10]->fromArray(array (
  'key' => 'ugm_github_username',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'GitHub Username',
  'description' => 'Your username at GitHub',
), '', true, true);
$systemSettings[11] = $modx->newObject('modSystemSetting');
$systemSettings[11]->fromArray(array (
  'key' => 'ugm_github_token',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'GitHub Token',
  'description' => 'Github token - available from your GitHub profile',
), '', true, true);
$systemSettings[12] = $modx->newObject('modSystemSetting');
$systemSettings[12]->fromArray(array (
  'key' => 'ugm_github_timeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'GitHub Timeout',
  'description' => 'Timeout in seconds for checking Github; default: 6',
), '', true, true);
$systemSettings[13] = $modx->newObject('modSystemSetting');
$systemSettings[13]->fromArray(array (
  'key' => 'ugm_versions_to_show',
  'value' => '5',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'Versions To Show',
  'description' => 'Number of versions to show in upgrade form; default: 5',
), '', true, true);
$systemSettings[14] = $modx->newObject('modSystemSetting');
$systemSettings[14]->fromArray(array (
  'key' => 'ugm_groups',
  'value' => 'Administrator',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Security',
  'name' => 'groups',
  'description' => 'group, or comma-separated list of groups, who will see the widget',
), '', true, true);
$systemSettings[15] = $modx->newObject('modSystemSetting');
$systemSettings[15]->fromArray(array (
  'key' => 'ugm_interval',
  'value' => '1 day',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Interval',
  'description' => 'Interval between checks -- Examples: 1 week, 3 days, 6 hours; default: 1 day',
), '', true, true);
$systemSettings[16] = $modx->newObject('modSystemSetting');
$systemSettings[16]->fromArray(array (
  'key' => 'ugm_last_check',
  'value' => '2018-10-12 12:50:00',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Last Check',
  'description' => 'Date and time of last check -- set automatically',
), '', true, true);
$systemSettings[17] = $modx->newObject('modSystemSetting');
$systemSettings[17]->fromArray(array (
  'key' => 'ugm_latest_version',
  'value' => '2.6.5-pl',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Latest Version',
  'description' => 'Latest version (at last check) -- set automatically',
), '', true, true);
$systemSettings[18] = $modx->newObject('modSystemSetting');
$systemSettings[18]->fromArray(array (
  'key' => 'ugm_hide_when_no_upgrade',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Hide When No Upgrade',
  'description' => 'Hide widget when no upgrade is available: default: No',
), '', true, true);
$systemSettings[19] = $modx->newObject('modSystemSetting');
$systemSettings[19]->fromArray(array (
  'key' => 'ugm_version_list_path',
  'value' => '{core_path}cache/upgrademodx/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'Versionlist Path',
  'description' => 'Path to versionlist file (minus the filename -- should end in a slash); Default: {core_path}cache/upgrademodx/',
), '', true, true);
return $systemSettings;
