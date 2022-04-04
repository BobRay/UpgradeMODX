<?php
/**
 * Processor file for UpgradeMODX extra
 *
 * Copyright 2015-2022 Bob Ray <https://bobsguides.com>
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

include_once 'ugmprocessor.class.php';

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
        sleep(2);
        $rootCoreConfig = $this->basePath. 'config.core.php';
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
                $setup = 'setup/includes/config.core.php';
                $target = $this->devMode ? $this->tempDir . 'test/' . $setup : $this->basePath . $setup;
                if (!file_put_contents($target, $content)) {
                    throw new Exception($this->modx->lexicon('ugm_could_not_write') . ' ' .
                        $this->modx->lexicon('ugm_to') .
                        ' ' . $target);
                }
            }

            /* Unlock Setup Directory */
            $dir = MODX_BASE_PATH . 'setup/.locked';
            if (is_dir($dir)) {
                rmdir($dir);
            }
            /* Log out all users before launching the setup */
            if (! $this->devMode) {
                $classPrefix = $this->modx->getVersionData()['version'] >= 3
                        ? 'MODX\Revolution\\'
                        : '';

                $sessionTable = $this->modx->getTableName($classPrefix . 'modSession');
                if ($this->modx->query("TRUNCATE TABLE {$sessionTable}") == false) {
                    $flushed = false;
                }
            }
        } else {
            throw new Exception($this->modx->lexicon('ugm_no_root_config_core'));
        }

    }

    /**
     * @return array|mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function process() {
        try {
            $this->prepareSetup();
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        if (!$this->hasErrors()) {
            $this->log($this->modx->lexicon('ugm_setup_prepared'));
        }
        return $this->prepareResponse($this->modx->lexicon('ugm_deleting_temp_files'));

    }
}

return 'UpgradeMODXPreparesetupProcessor';
