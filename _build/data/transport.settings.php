<?php
/**
 * systemSettings transport file for UpgradeMODX extra
 *
 * Copyright 2015-2022 Bob Ray <https://bobsguides.com>
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
  'key' => 'ugm_hide_when_no_upgrade',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_hide_when_no_upgrade',
  'description' => 'setting_ugm_hide_when_no_upgrade_desc',
), '', true, true);
$systemSettings[2] = $modx->newObject('modSystemSetting');
$systemSettings[2]->fromArray(array (
  'key' => 'ugm_version_list_path',
  'value' => '{core_path}cache/upgrademodx/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_version_list_path',
  'description' => 'setting_ugm_version_list_path_desc',
), '', true, true);
$systemSettings[3] = $modx->newObject('modSystemSetting');
$systemSettings[3]->fromArray(array (
  'key' => 'ugm_latest_version',
  'value' => '2.8.4-pl',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_latest_version',
  'description' => 'setting_ugm_latest_version_desc',
), '', true, true);
$systemSettings[4] = $modx->newObject('modSystemSetting');
$systemSettings[4]->fromArray(array (
  'key' => 'ugm_verbose',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'setting_ugm_verbose',
  'description' => 'setting_ugm_verbose_desc',
), '', true, true);
$systemSettings[5] = $modx->newObject('modSystemSetting');
$systemSettings[5]->fromArray(array (
  'key' => 'ugm_interval',
  'value' => '1 day',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_interval',
  'description' => 'setting_ugm_interval_desc',
), '', true, true);
$systemSettings[6] = $modx->newObject('modSystemSetting');
$systemSettings[6]->fromArray(array (
  'key' => 'ugm_last_check',
  'value' => '2022-05-19 00:33:18',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_last_check',
  'description' => 'setting_ugm_last_check_desc',
), '', true, true);
$systemSettings[7] = $modx->newObject('modSystemSetting');
$systemSettings[7]->fromArray(array (
  'key' => 'ugm_temp_dir',
  'value' => '{base_path}ugmtemp/',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_temp_dir',
  'description' => 'setting_ugm_temp_dir_desc',
), '', true, true);
$systemSettings[8] = $modx->newObject('modSystemSetting');
$systemSettings[8]->fromArray(array (
  'key' => 'ugm_versionlist_api_url',
  'value' => '//api.github.com/repos/modxcms/revolution/tags',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_versionlist_api_url',
  'description' => 'setting_ugm_versionlist_api_url_desc',
), '', true, true);
$systemSettings[9] = $modx->newObject('modSystemSetting');
$systemSettings[9]->fromArray(array (
  'key' => 'ugm_file_version',
  'value' => '3.0.1-pl',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Widget',
  'name' => 'setting_ugm_file_version',
  'description' => 'setting_ugm_file_version_desc',
), '', true, true);
$systemSettings[10] = $modx->newObject('modSystemSetting');
$systemSettings[10]->fromArray(array (
  'key' => 'ugm_cert_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'setting_ugm_cert_path',
  'description' => 'setting_ugm_cert_path_desc',
), '', true, true);
$systemSettings[11] = $modx->newObject('modSystemSetting');
$systemSettings[11]->fromArray(array (
  'key' => 'ugm_force_pcl_zip',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'setting_ugm_force_pcl_zip',
  'description' => 'setting_ugm_force_pcl_zip_desc',
), '', true, true);
$systemSettings[12] = $modx->newObject('modSystemSetting');
$systemSettings[12]->fromArray(array (
  'key' => 'ugm_modx_timeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'setting_ugm_modx_timeout',
  'description' => 'setting_ugm_modx_timeout_desc',
), '', true, true);
$systemSettings[13] = $modx->newObject('modSystemSetting');
$systemSettings[13]->fromArray(array (
  'key' => 'ugm_ssl_verify_peer',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Download',
  'name' => 'setting_ugm_ssl_verify_peer',
  'description' => 'setting_ugm_ssl_verify_peer_desc',
), '', true, true);
$systemSettings[14] = $modx->newObject('modSystemSetting');
$systemSettings[14]->fromArray(array (
  'key' => 'ugm_language',
  'value' => 'en',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'setting_ugm_language',
  'description' => 'setting_ugm_language_desc',
), '', true, true);
$systemSettings[15] = $modx->newObject('modSystemSetting');
$systemSettings[15]->fromArray(array (
  'key' => 'ugm_pl_only',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'setting_ugm_pl_only',
  'description' => 'setting_ugm_pl_only_desc',
), '', true, true);
$systemSettings[16] = $modx->newObject('modSystemSetting');
$systemSettings[16]->fromArray(array (
  'key' => 'ugm_github_username',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'setting_ugm_github_username',
  'description' => 'setting_ugm_github_username_desc',
), '', true, true);
$systemSettings[17] = $modx->newObject('modSystemSetting');
$systemSettings[17]->fromArray(array (
  'key' => 'ugm_github_token',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'setting_ugm_github_token',
  'description' => 'setting_ugm_github_token_desc',
), '', true, true);
$systemSettings[18] = $modx->newObject('modSystemSetting');
$systemSettings[18]->fromArray(array (
  'key' => 'ugm_github_timeout',
  'value' => '6',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'GitHub',
  'name' => 'setting_ugm_github_timeout',
  'description' => 'setting_ugm_github_timeout_desc',
), '', true, true);
$systemSettings[19] = $modx->newObject('modSystemSetting');
$systemSettings[19]->fromArray(array (
  'key' => 'ugm_groups',
  'value' => 'Administrator',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Security',
  'name' => 'setting_ugm_groups',
  'description' => 'setting_ugm_groups_desc',
), '', true, true);
$systemSettings[20] = $modx->newObject('modSystemSetting');
$systemSettings[20]->fromArray(array (
  'key' => 'ugm_versions_to_show',
  'value' => '5',
  'xtype' => 'textfield',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'setting_ugm_versions_to_show',
  'description' => 'setting_ugm_versions_to_show_desc',
), '', true, true);
$systemSettings[21] = $modx->newObject('modSystemSetting');
$systemSettings[21]->fromArray(array (
  'key' => 'ugm_show_modx3',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'upgrademodx',
  'area' => 'Form',
  'name' => 'setting_ugm_show_modx3',
  'description' => 'setting_ugm_show_modx3_desc',
), '', true, true);
return $systemSettings;
