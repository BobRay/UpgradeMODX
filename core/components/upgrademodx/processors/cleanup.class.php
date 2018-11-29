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

include_once 'ugmprocessor.class.php';

class UpgradeMODXCleanupProcessor extends UgmProcessor {

    public $languageTopics = array('upgrademodx:default');

    function initialize() {
        /* Initialization here */
        parent::initialize();
        $this->name = 'Cleanup Processor';
        $this->log($this->modx->lexicon('ugm_deleting_temp_files'));
        return true;
    }

    /**
     * Copy root config.core.php to setup/includes
     * after adding setup key
     * @throws Exception
     */

    /* ToDo: Add option to save downloaded .zip file */
    public function cleanUp() {
        $rootCoreConfig = $this->basePath . 'config.core.php';
        $success = true;
        if (!$this->customTempDir) {
            $this->rrmdir($this->tempDir);
        } else {
            $this->rrmdir($this->unzippedDir);
        }
    }

    /** Recursive remove dir function.
     *  Removes a directory and all its children */
    public function rrmdir($dir) {
        $dir = rtrim($dir, '/\\');
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $prefix = substr($object, 0, 4);
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        @unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            $success = @rmdir($dir);
        }
    }

    public function process() {
        try{
            $this->cleanUp();
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        if (! $this->hasErrors()) {
            $this->log($this->modx->lexicon('ugm_temp_files_deleted'));
            $this->log($this->modx->lexicon('ugm_launching_setup'));

        }
        return $this->prepareResponse($this->modx->lexicon('ugm_launching_setup'));

    }
}

return 'UpgradeMODXCleanupProcessor';
