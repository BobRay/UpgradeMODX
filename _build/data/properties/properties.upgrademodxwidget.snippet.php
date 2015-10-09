<?php
/**
 * Properties file for UpgradeMODXWidget snippet
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
 * Created on 08-17-2015
 *
 * @package upgrademodx
 * @subpackage build
 */




$properties = array (
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
    'area' => '',
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
    'area' => '',
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
    'area' => '',
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
    'area' => '',
  ),
  'lastCheck' => 
  array (
    'name' => 'lastCheck',
    'desc' => 'ugm_lastCheck_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '2015-08-28 01:09:12',
    'lexicon' => 'upgrademodx:properties',
    'area' => '',
  ),
  'latestVersion' => 
  array (
    'name' => 'latestVersion',
    'desc' => 'ugm_latestVersion_desc',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '2.4.1-pl',
    'lexicon' => 'upgrademodx:properties',
    'area' => '',
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
    'area' => '',
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
    'area' => '',
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
    'area' => '',
  ),
);

return $properties;

