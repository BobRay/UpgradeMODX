<?php
/**
 * dashboardWidgets transport file for UpgradeModx extra
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
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
  'name' => 'UpgradeModx',
  'description' => 'Upgrade Modx Widget',
  'type' => 'snippet',
  'content' => 'UpgradeModxWidget',
  'namespace' => 'upgrademodx',
  'lexicon' => 'upgrademodx:default',
  'size' => 'half',
  'name_trans' => 'UpgradeModx',
  'description_trans' => 'Upgrade Modx Widget',
), '', true, true);
return $dashboardWidgets;
