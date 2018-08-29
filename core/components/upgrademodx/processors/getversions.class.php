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

include_once ('ugmprocessor.class.php');

class GetVersionsProcessor extends UgmProcessor {

    public $languageTopics = array('upgrademodx:default');
    /** @var $upgrade UpgradeMODX */
    public $upgrade; /* UpgradeMODX class */
    protected $username = '';
    protected $token = '';


    protected $versionArray = array();

    function initialize() {
        parent::initialize();
        $this->name = 'Get Versions Processor';
        $this->upgrade = new UpgradeMODX($this->modx);
        $this->upgrade->init($this->props);
        return true;
    }

    public function process() {
        $o = $this->upgrade->createVersionList();
        return $this->prepareResponse($o, $this->upgrade->getVersionArray());
    }
}

return 'GetVersionsProcessor';
