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

class UpgradeMODXCopyfilesProcessor extends UgmProcessor {
    var $fileCount = 0;

    function initialize() {
        /* Initialization here */
        parent::initialize();
        $this->name = 'Copy Files Processor';
        $this->log($this->modx->lexicon('ugm_copying_files'));
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
        $corePath = $this->normalize($corePath);
        $pPath = $this->normalize($pPath);
        return stripos($pPath, $corePath) !== false;
    }


    /**
     * Get Array of directories to process
     *
     * @param array $directories
     * @return array
     */
    public function getDirectories($directories = array()) {
        $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);
        $connectorsPath = $this->modx->getOption('connectors_path', null, MODX_CONNECTORS_PATH);
        $processorsPath = $this->modx->getOption('processors_path', null, MODX_PROCESSORS_PATH);
        if (empty($directories)) {
            $directories = array(
                'setup' => $this->basePath . 'setup/',
                'core' => $this->modxCorePath,
                'manager' => $managerPath,
                'connectors' => $connectorsPath,
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
        $path = $this->processorsUnderCore($this->modxCorePath, $processorsPath);
        if (! $path) { // processors not under core
            $directories['core/model/modx/processors'] = $processorsPath;
        }

        /* Normalize directory paths */
        $directories = $this->normalize($directories);

        return $directories;
    }

    /** @throws Exception */
    public function copyFiles($sourceDir, $directories) {

        /* Normalize directory paths */
        $this->normalize($directories);
        $this->normalize($sourceDir);


        /* Copy directories */
         /*
         * @var  $source string
         * @var  $target string
         */
        foreach ($directories as $source => $target) {
            if (empty($target)) {
                throw new Exception('EMPTY1: ' . $source. ' => ' . $target);
            }
            $this->mmkDir($target);
            set_time_limit(0);
            $this->recurse_copy($sourceDir . '/' . $source, $target);
            $this->log('    ' . $this->modx->lexicon($this->modx->lexicon('ugm_copied') .
                    ' ' . $source . ' ' .
                    $this->modx->lexicon('ugm_to') . ' ' . $target));
        }

    }


    /**
     * @param $source string
     * @param $dest string
     * @return bool
     * @throws Exception
     */
    public function recurse_copy($source, $dest) {

        if (empty($source)) {
            throw new Exception('[Copy Files Processor] Source path is empty -- Source = ' . $source);
        }
        if (empty($dest)) {
            throw new Exception('[Copy Files Processor] Destination path is empty -- Path = ' . $dest);
        }

        /* Skip these (setup/includes/config.core.php copied elsewhere) */
        $configCore = 'config.core.php';

        // Check for symlinks
        if (is_link($source)) {
            $this->fileCount++;
            return symlink(readlink($source), $dest);
        }
        // Simple copy for a file
        if (is_file($source)) {
            $this->fileCount++;
            return copy($source, $dest);
        }

        if (is_dir($source)) {
            $this->fileCount++;
        }
        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== ($entry = $dir->read())) {
            // Skip pointers and config files
            if ($entry == '.' || $entry == '..' || $entry == $configCore) {
                continue;
            }
            // Deep copy directories
            $this->recurse_copy("{$source}/{$entry}", "{$dest}/{$entry}");
        }
        // Clean up
        $dir->close();
        return true;
    }


    public function process() {

        $version = str_replace('.zip', '' , $this->zipFileName);

        /* Get directories for file copy */
        $directories = $this->getDirectories();
        $directories = $this->normalize($directories);
        /* set start time */
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tstart = $mtime;
        set_time_limit(0);
        $source = $this->unzippedDir . '/' . $version;
        $dest = $this->devMode ? $this->testDir : $this->basePath;
        try {
            copy($source . '/ht.access', $dest . 'ht.access');
            copy($source . '/index.php', $dest . 'index.php');
            $this->copyFiles($source, $directories);
            /* We do need this one */
            copy($source . '/setup/includes/config.core.php', $dest . '/setup/includes/config.core.php');
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
        /* report how long it took */
        $output = '';
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tend = $mtime;
        $totalTime = ($tend - $tstart);
        $totalTime = sprintf("%2.4f s", $totalTime);
        $output .= "\n    " . 'Copy Time' .
            ': ' . $totalTime;

        $output .= ' -- ' . $this->modx->lexicon('ugm_files_copied') . ' ' . $this->fileCount;
        $this->log($output);
        return $this->prepareResponse($this->modx->lexicon('ugm_preparing_setup'));

    }
}

return 'UpgradeMODXCopyfilesProcessor';
