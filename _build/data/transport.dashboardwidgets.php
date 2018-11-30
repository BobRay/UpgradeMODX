<?php
/**
 * dashboardWidgets transport file for UpgradeMODX extra
 *
 * Copyright 2015-2018 by Bob Ray <https://bobsguides.com>
 * Created on 08-15-2015
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
/* @var xPDOObject[] $dashboardWidgets */


$dashboardWidgets = array();

$dashboardWidgets[1] = $modx->newObject('modDashboardWidget');
$dashboardWidgets[1]->fromArray(array (
  'id' => 1,
  'name' => 'Upgrade MODX',
  'description' => 'Upgrade MODX Widget',
  'type' => 'snippet',
  'content' => 'UpgradeMODXWidget',
  'namespace' => 'upgrademodx',
  'lexicon' => 'upgrademodx:default',
  'size' => 'half',
  'name_trans' => 'Upgrade MODX',
  'description_trans' => 'Upgrade MODX Widget',
), '', true, true);
return $dashboardWidgets;
