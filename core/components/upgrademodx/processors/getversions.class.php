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
    // public $defaultSortField = 'name';
    // public $defaultSortDirection = 'ASC';
    // public $ids;

    function initialize() {
        $corePath = $this->modx->getOption('ugm.core_path', null, $this->modx->getOption('core_path') . 'components/upgrademodx/');
        require_once $corePath . 'model/upgrademodx.class.php';
        $this->corePath = $corePath;
        $ugm = new UpgradeMODX($this->modx);
        return true;
    }

    /* For built-in processors (create, update, duplicate, remove),
       this method can be removed */
    public function process() {

        /* perform action here */

        $o = '<h3>Hello World</h3>';


        return $this->success($o);

    }

}

return 'GetVersionsProcessor';
