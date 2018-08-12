<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
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

class UpgradeMODXDownloadfilesProcessor extends UgmProcessor {
//    public $languageTopics = array('upgrademodx:default');
      public $client = null;
      public $sourceUrl = '';
      public $destinationPath = '';


    function initialize() {
        /** @var $scriptProperties array */
        parent::initialize();
        $this->name = 'Download Files Processor';

        /* Write directly because we want to truncate the file */
        $fp = fopen($this->logFilePath, 'w');
        if ($fp) {
            fwrite($fp, 'UpgradeMODX Log -- ' . strftime('%A %B %C, %G %I:%M %p'));
            fclose($fp);
        } else {
            $this->addError('Could not open ' . $this->logFilePath);
        }
        $this->log($this->modx->lexicon('ugm_downloading_files'));

        $corePath = $this->corePath;
        require_once $corePath . 'vendor/autoload.php';
        $version = $this->getProperty('version', null);
        $shortVersion = strtok($version, '-');
        $this->sourceUrl = 'https://modx.s3.amazonaws.com/releases/' . $shortVersion . '/modx-' . $version . '.zip';
        if ($this->devMode) {
            // $this->sourceUrl = 'http://localhost/addons/sitecheck.zip';
        }

        $_SESSION['ugm_version'] = '/modx-' . $version;

        $this->destinationPath = $this->tempDir . 'modx.zip';
        $this->client = new Client();
        return true;
    }

    /** @throws Exception */
    public function download() {
        $client = new Client();
            $destFile = fopen($this->destinationPath, 'w');
            if (! $destFile) {
                $msg = '[Download Files Processor] ' .
                    $this->modx->lexicon('ugm_could_not_open') . ' ' .
                    $this->destinationPath . ' ' . $this->modx->lexicon('ugm_for_writing');
                throw new Exception($msg);

            } else if (file_exists($this->destinationPath) && $this->devMode) {
                return;
            }

        set_time_limit(0);

            $response = $client->request('GET', $this->sourceUrl, [
                'headers' => array(
                    'Cache-Control' => 'no-cache',
                    'Accept' => 'application/zip'
                ),
                'sink' => $destFile,
            ]);
            $msg = $this->modx->lexicon('ugm_downloaded~~Downloaded'  . ' ' . $_SESSION['ugm_version'] . ' (-> ' . $this->destinationPath . ')');
            $this->log($msg);

    }

    public function process() {
        try {
            $this->download();
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
        /* message for next processor */
        return $this->prepareResponse($this->modx->lexicon('ugm_unzipping_files'));

    }
}

return 'UpgradeMODXDownloadfilesProcessor';
