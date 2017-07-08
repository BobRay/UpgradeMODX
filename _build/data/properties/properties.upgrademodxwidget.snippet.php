<?php
/**
 * Properties file for UpgradeMODXWidget snippet
 *
 * Copyright 2015-2017 Bob Ray <https://bobsguides.com>
 * Created on 08-17-2015
 *
 * @package upgrademodx
 * @subpackage build
 */




$properties = array (
  'attempts' => 
  array (
    'name' => 'attempts',
    'desc' => 'ubm_attempts_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '2',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Download',
  ),
  'forceFopen' => 
  array (
    'name' => 'forceFopen',
    'desc' => 'ugm_forceFopen_desc',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => false,
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Download',
  ),
  'forcePclZip' => 
  array (
    'name' => 'forcePclZip',
    'desc' => 'ugm_forcePclZip_desc',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => false,
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Download',
  ),
  'modxTimeout' => 
  array (
    'name' => 'modxTimeout',
    'desc' => 'ugm_modx_timeout_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '6',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Download',
  ),
  'ssl_verify_peer' => 
  array (
    'name' => 'ssl_verify_peer',
    'desc' => 'ugm_ssl_verify_peer_desc',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => true,
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Download',
  ),
  'language' => 
  array (
    'name' => 'language',
    'desc' => 'ugm_language_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => 'en',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Form',
  ),
  'plOnly' => 
  array (
    'name' => 'plOnly',
    'desc' => 'ugm_plOnly_desc',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => true,
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Form',
  ),
  'versionsToShow' => 
  array (
    'name' => 'versionsToShow',
    'desc' => 'ugm_versionsToShow_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '5',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Form',
  ),
  'githubTimeout' => 
  array (
    'name' => 'githubTimeout',
    'desc' => 'ugm_github_timeout_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '6',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'GitHub',
  ),
  'github_token' => 
  array (
    'name' => 'github_token',
    'desc' => 'ugm_github_token_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'GitHub',
  ),
  'github_username' => 
  array (
    'name' => 'github_username',
    'desc' => 'ugm_github_username_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'GitHub',
  ),
  'groups' => 
  array (
    'name' => 'groups',
    'desc' => 'ugm_groups_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => 'Administrator',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Security',
  ),
  'hideWhenNoUpgrade' => 
  array (
    'name' => 'hideWhenNoUpgrade',
    'desc' => 'ugm_hideWhenNoUpgrade_desc',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => false,
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Widget',
  ),
  'interval' => 
  array (
    'name' => 'interval',
    'desc' => 'ugm_interval_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '1 week',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Widget',
  ),
  'lastCheck' => 
  array (
    'name' => 'lastCheck',
    'desc' => 'ugm_lastCheck_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Widget',
  ),
  'latestVersion' => 
  array (
    'name' => 'latestVersion',
    'desc' => 'ugm_latestVersion_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '2.5.4-pl',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Widget',
  ),
  'versionListPath' => 
  array (
    'name' => 'versionListPath',
    'desc' => 'ugm_version_list_path_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '{core_path}cache/upgrademodx/',
    'lexicon' => 'upgrademodx:properties',
    'area' => 'Widget',
  ),
);

return $properties;

