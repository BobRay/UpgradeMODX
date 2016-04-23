<?php
/**
 * chunks transport file for UpgradeMODX extra
 *
 * Copyright 2015-2016 by Bob Ray <http://bobsguides.com>
 * Created on 08-13-2015
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
/* @var xPDOObject[] $chunks */


$chunks = array();

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array (
  'id' => 1,
  'property_preprocess' => false,
  'name' => 'UpgradeMODXSnippetScriptSource',
  'description' => 'Source for MODX Upgrade script. Used internally',
  'properties' => 
  array (
  ),
), '', true, true);
$chunks[1]->setContent(file_get_contents($sources['source_core'] . '/elements/chunks/upgrademodxsnippetscriptsource.chunk.php'));

$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array (
  'id' => 2,
  'property_preprocess' => false,
  'name' => 'UpgradeMODXTpl',
  'description' => 'Tpl chunk for alert widget',
  'properties' => NULL,
), '', true, true);
$chunks[2]->setContent(file_get_contents($sources['source_core'] . '/elements/chunks/upgrademodxtpl.chunk.html'));

return $chunks;
