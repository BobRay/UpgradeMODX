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

class UpgradeMODXUnzipfilesProcessor extends UgmProcessor {

    public $languageTopics = array('upgrademodx:default');
    public $source;
    public $destination;
    public $forcePclZip = false;


    function initialize() {
        /* Initialization here */
        parent::initialize();
        $this->log($this->modx->lexicon('ugm_unzipping_files'));
        $this->forcePclZip = $this->modx->getOption('force_pcl_zip', null ,false, true);
        $this->name = 'Unzip Files Processor';
        $this->source = $this->tempDir . $this->zipFileName;
        $this->destination = $this->tempDir . 'unzipped';
        $this->log("    Source: " . $this->source);
        $this->log("    Destination: " . $this->destination);
        return true;
    }

    /** Make sure $source and $destination are usable
     * @throws Exception
     *  @var $source string
     * @var $destination string
     */

    public function validate($source, $destination) {
        clearstatcache();
        if (!file_exists($source)) {
            throw new Exception($this->modx->lexicon('ugm_no_downloaded_file') . ': ' . $source);
        }

        if (!is_dir($destination)) {
            @$this->mmkDir($destination);
        }

        if (!is_dir($destination)) {
            throw new Exception($this->modx->lexicon('ugm_could_not_create_directory') . ': ' . $destination);
        } else {
            if (!is_writable($destination)) {
                throw new Exception($this->modx->lexicon('ugm_directory_not_writable') . ': ' . $destination);
            }
        }

    }

    /** @throws Exception
     */
    public function unZip($forcePclZip = false) {
        $source = $this->source;
        $destination = $this->destination;
        $this->log('    Zip File Name ' . $this->zipFileName);
        $base = str_replace('.zip', '', $this->zipFileName);

        try {
            $this->validate($source, $destination);
        } catch (Exception$e) {
            $this->addError($e->getMessage());
            return false;
        }

        $corePath = $this->modxCorePath;
        $status = true;
        if ((!$forcePclZip) && class_exists('ZipArchive', false)) {
            $zip = new ZipArchive;
            if ($zip instanceof ZipArchive) {
                $open = $zip->open($source, ZipArchive::CHECKCONS);
                $fileCount = (int) ($zip->numFiles);
                $fileCount -= 7;
                $this->log('    ' . $this->modx->lexicon('ugm_files_to_extract') . ' ' . $fileCount);
                $this->log('    ' . $this->modx->lexicon('ugm_destination') . ': ' . $destination);
                $this->log('    ' . $this->modx->lexicon('ugm_source') . ': ' . $source);

                // throw new Exception('Aborting');

                if ($open === true) {
                    $result = $zip->extractTo($destination);
                    if ($result === false) {
                        /* Yes, this is fucking nuts, but it's necessary on some platforms */
                        $result = $zip->extractTo($destination);
                        if ($result === false) {
                            $msg = $zip->getStatusString();
                            throw new Exception($msg);
                        }
                    }
                    $zip->close();
                } else {
                    $status = 'Could not open ZipArchive ' . $source . ' ' . $zip->getStatusString();
                }
            } else {
                $status = 'Could not instantiate ZipArchive';
            }
        } else {
            $zipClass = $corePath . 'xpdo/compression/pclzip.lib.php';
            if (file_exists($zipClass)) {
                include $corePath . 'xpdo/compression/pclzip.lib.php';
                $archive = new PclZip($source);
                if ($archive->extract(PCLZIP_OPT_PATH, $destination) == 0) {
                    $status = 'Extraction with PclZip failed - Error : ' . $archive->errorInfo(true);
                }
            } else {
                $status = 'Neither ZipArchive, nor PclZip were available to unzip the archive';
            }
        }
        if ($status === true) {
            $msg = $this->modx->lexicon('ugm_unzipped') .
                ' ' . 'modx.zip' . ' -> ' . $destination;
            $this->log($msg);
        }

        return $status;
    }

    public function process() {
        try {
            $this->unZip($this->forcePclZip);
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
        return $this->prepareResponse($this->modx->lexicon('ugm_copying_files'));

    }
}


return 'UpgradeMODXUnzipfilesProcessor';




