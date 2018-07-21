<?php
/**
 * Processor file for Example extra
 *
 * Copyright 2013 by Bob Ray <https://bobsguides.com>
 * Created on 06-18-2014
 *
 * Example is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Example is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Example; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package UpgradeMODX
 * @subpackage processors
 */

/* @var $modx modX */


class GetVersionsProcessor extends modProcessor {

    public $classKey = 'modChunk';
    public $languageTopics = array('upgrademodx:default');
    public $corePath;
    /** @var $upgrade UpgradeMODX */
    public $upgrade; /* UpgradeMODX class */
    public $method = 'curl';
    // public $defaultSortField = 'name';
    // public $defaultSortDirection = 'ASC';
    // public $ids;

    function initialize() {
        $corePath = $this->modx->getOption('ugm.core_path', null, $this->modx->getOption('core_path') . 'components/upgrademodx/');
        require_once $corePath . 'model/upgrademodx/upgrademodx.class.php';
        $this->corePath = $corePath;
        $this->upgrade = new UpgradeMODX($this->modx);
        return true;
    }

    /* For built-in processors (create, update, duplicate, remove),
       this method can be removed */
    public function process() {

        $o = $this->createVersionList($this->method);

        /* perform action here */


       //  $o = '<h3>Version List</h3>';


        return $this->success($o);

    }

    public function checkPermissions() {

        return (bool) $this->modx->user->isMember('Administrator');

    }

    public function createVersionList($method) {
        $output = '';
        $versions = $this->upgrade->getJSONFromGitHub($method);
        $versions = $this->upgrade->finalizeVersionArray($versions);
       //  return print_r($versions, true);
        $itemGrid = array();
        foreach ($versions as $ver => $item) {
            $itemGrid[$item['tree']][$ver] = $item;
        }
        $i = 0;
        foreach ($itemGrid as $tree => $item) {
            $output .= "\n" . '<div class="column">';;
            foreach ($item as $version => $itemInfo) {
                $selected = $itemInfo['selected'] ? ' checked' : '';
                $current = $itemInfo['current'] ? ' &nbsp;&nbsp;(' . '[[%ugm_current_version_indicator]]' . ')' : '';
                $i = 0;
                $output .= <<<EOD
                    <label><input type="radio"{$selected} name="modx" value="$version">
                    <span>{$itemInfo['name']} $current</span>
                    </label>
EOD;
            $i++;
            } // end inner foreach loop
        } // end outer foreach loop
        return $output . "\n</div>" ;
    }
}

return 'GetVersionsProcessor';
