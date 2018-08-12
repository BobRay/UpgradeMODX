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

    /**
     * Copy root config.core.php to setup/includes
     * after adding setup key
     * @throws Exception
     */
    public function prepareSetup() {
        $rootCoreConfig = MODX_BASE_PATH . 'config.core.php';
        $success = true;
        if (file_exists($rootCoreConfig)) {
            $newStr = "define('MODX_SETUP_KEY', '@traditional@');\n?>";
            $content = file_get_contents($rootCoreConfig);
            if (strpos($content, 'MODX_SETUP_KEY') === false) {
                if (strpos($content, '?>') !== false) {
                    $content = str_replace('?>', $newStr, $content);
                } else {
                    $content .= "\n" . $newStr;
                }
                if (!file_put_contents(MODX_BASE_PATH . 'setup/includes/config.core.php', $content)) {
                    throw new Exception($this->modx->lexicon('ugm_could_not_write') . ' ' . $this->modx->lexicon('ugm_to' .
                       'setup/includes/config.core.php'));

                }
            }
        } else {
            throw new Exception($this->modx->lexicon('ugm_no_root_config_core~~Could not find root config.core.php'));
        }

    }

    public function process() {
        try{
            $this->prepareSetup();
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        if (! $this->hasErrors()) {
            $this->log($this->modx->lexicon('ugm_setup_prepared'));
            $this->log($this->modx->lexicon('ugm_launching_setup'));
        }
        return $this->prepareResponse($this->modx->lexicon('ugm_launching_setup'));

    }
}

return 'UpgradeMODXPreparesetupProcessor';
