<?php
/**
 * Properties file for UpgradeModxWidget snippet
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
 * Created on 08-17-2015
 *
 * @package upgrademodx
 * @subpackage build
 */




$properties = array (
  'VersionsToShow' => 
  array (
    'name' => 'versionsToShow',
    'desc' => 'Number of versions to show in upgrade form (not widget); default: 5',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '5',
    'lexicon' => 'upgrademodx:default',
    'area' => '',
  ),
  'hideWhenNoUpdate' => 
  array (
    'name' => 'hideWhenNoUpdate',
    'desc' => 'Hide widget when no update is available: default: No',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => false,
    'lexicon' => 'upgrademodx:default',
    'area' => '',
  ),
  'interval' => 
  array (
    'name' => 'interval',
    'desc' => 'Interval between checks -- Examples: 1 week, 3 days, 6 hours; default: 1 week',
    'type' => 'textfield',
    'options' => 
    array (
    ),
    'value' => '60 seconds',
    'lexicon' => 'upgrademodx:default',
    'area' => '',
  ),

  'plOnly' => 
  array (
    'name' => 'plOnly',
    'desc' => 'Show only pl (stable) versions; default: yes',
    'type' => 'combo-boolean',
    'options' => 
    array (
    ),
    'value' => false,
    'lexicon' => 'upgrademodx:default',
    'area' => '',
  ),
  'lastCheck' =>
  array(
  'name' => 'lastCheck',
          'desc' => 'Date and time of last check -- set automatically',
          'type' => 'textfield',
          'options' =>
              array(),
          'value' => '2015-08-17 00:13:44',
          'lexicon' => 'upgrademodx:default',
          'area' => '',
      ),
    'latestVersion' =>
        array(
            'name' => 'latestVersion',
            'desc' => 'Latest version (at last check) -- set automatically',
            'type' => 'textfield',
            'options' =>
                array(),
            'value' => '',
            'lexicon' => 'upgrademodx:default',
            'area' => '',
        ),
);

return $properties;

