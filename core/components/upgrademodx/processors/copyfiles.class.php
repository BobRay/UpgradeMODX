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

class UpgradeMODXCopyfilesProcessor extends UgmProcessor {

    function initialize() {
        /* Initialization here */
        parent::initialize();
        $this->name = 'Copy Files Processor';
        return true;
    }

    public function normalize($paths) {
        if (is_array($paths)) {
            foreach ($paths as $k => $v) {
                $v = str_replace('\\', '/', rtrim($v, '/\\'));
                $paths[$k] = $v;
            }
        } else {
            $paths = str_replace('\\', '/', rtrim($paths, '/\\'));
        }
        return $paths;
    }

    /** Return true if processors are under core;
     *  false if not;
     *
     * @var $corePath string
     * @var $pPath string
     * @return boolean
     */
    public function processorsUnderCore($corePath, $pPath) {
        /* If processors are under the core, no need to do processors path */
        $p = pathinfo($corePath, PATHINFO_DIRNAME);
        $p = array_filter(explode('/', $p));
        $coreDir = array_pop($p);
        return stripos($pPath, $coreDir) !== false;
    }


    /**
     * Get Array of directories to process
     *
     * @param array $directories
     * @return array
     */
    public function getDirectories($directories = array()) {
        if (empty($directories)) {
            $directories = array(
                'setup' => MODX_BASE_PATH . 'setup/',
                'core' => MODX_CORE_PATH,
                'manager' => MODX_MANAGER_PATH,
                'connectors' => MODX_CONNECTORS_PATH,
            );
        }

        if ($this->devMode) {
            $directories = array(
                'setup' => $this->testDir . 'setup/',
                'core' => $this->testDir . 'core/',
                'manager' => $this->testDir . 'manager/',
                'connectors' => $this->testDir . 'connectors/',
            );
        }

        /* See if we need to do processors path */
        $path = $this->processorsUnderCore(MODX_CORE_PATH, MODX_PROCESSORS_PATH);
        if (! $path) { // processors not under core
            $directories['core/model/modx/processors'] = MODX_PROCESSORS_PATH;
        }

        /* Normalize directory paths */
        $directories = $this->normalize($directories);

        return $directories;
    }

    public function copyFiles($sourceDir, $directories) {

        /* Normalize directory paths */
        $this->normalize($directories);
        $this->normalize($sourceDir);

        /* Copy directories */
        foreach ($directories as $source => $target) {
            if (empty($target)) {
                throw new Exception('EMPTY1: ' . $source. ' => ' . $target);
            }

            $this->mmkDir($target);
            set_time_limit(0);
            $this->copyFolder($sourceDir . '/' . $source, $target);
        }

    }

    public function copyFolder($src, $dest) {

        $fp = fopen ($this->tempDir . 'log.txt', 'w');

        $path = realpath($src);
        $dest = realpath($dest);
        if (empty($path)) {
            throw new Exception('Path is empty -- Dest = ' . $dest);
        }
        if (empty($dest)) {
            throw new Exception('Dest is empty -- Path = ' . $path);
        }
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {
            $startsAt = substr(dirname($name), strlen($path));
            if (empty($dest . $startsAt)) {
                throw new Exception('EMPTY2: ' . $name . ' => ' . $startsAt);
            }
            $this->mmkDir($dest . $startsAt, true);
            if ($object->isDir()) {
                if (empty($dest . substr($name, strlen($path)))) {
                    throw new Exception('EMPTY3');
                }
                $this->mmkDir($dest . substr($name, strlen($path)));
            }
            if (is_writable($dest . $startsAt) and $object->isFile()) {
                $success = copy((string)$name, $dest . $startsAt . DIRECTORY_SEPARATOR . basename($name));
                if (! $success)  {
                    fwrite($fp, 'Could not copy ' . $dest . $startsAt . DIRECTORY_SEPARATOR . basename($name));
                }
            } else {
                $success = false;
                //die("Not a File or not writable");
            }
            if (! $success) {

               // $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('ugm_could_not_copy~~Could not copy'), ' ' .
                   // (string)$name, $dest . $startsAt . DIRECTORY_SEPARATOR . basename($name));
               //  die('Not a success');
            }
        }
        fclose($fp);
    }

    public function process() {

        $version = $_SESSION['ugm_version'];
       //  $this->modx->log(modX::LOG_LEVEL_ERROR, 'SourceDir ' . $this->unzippedDir . $version);
        /* Get directories for file copy */
        $directories = $this->getDirectories();
        $directories = $this->normalize($directories);
        try {
            $this->copyFiles($this->unzippedDir . $version, $directories);
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }

        return $this->prepareResponse($this->modx->lexicon('ugm_preparing_setup'));

    }
}

return 'UpgradeMODXCopyfilesProcessor';
