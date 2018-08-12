<?php
/**
 * Processor file for UpgradeMODX extra
 *
 * Copyright 2015-2018 by Bob Ray <https://bobsguides.com>
 * Created on 07-16-2018
 *
 * UpgradeMODX is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UpgradeMODX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UpgradeMODX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package upgrademodx
 * @subpackage processors
 */

/* @var $modx modX */

include 'ugmprocessor.class.php';

class UpgradeMODXPreparesetupProcessor extends UgmProcessor {

    public $languageTopics = array('upgrademodx:default');

    function initialize() {
        /* Initialization here */
        parent::initialize();
        $this->name = 'Prepare Setup Processor';
        $this->log($this->modx->lexicon('ugm_preparing_setup'));
        return true;
    }

    public function process() {

        /* perform action here */
        if (! $this->hasErrors()) {
            $this->log($this->modx->lexicon('ugm_setup_prepared'));
            $this->log($this->modx->lexicon('ugm_launching_setup'));
        }
        return $this->success($this->modx->lexicon('ugm_launching_setup'));

    }
}

return 'UpgradeMODXPreparesetupProcessor';
