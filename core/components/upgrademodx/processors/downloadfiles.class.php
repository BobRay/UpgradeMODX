<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Processor file for UpgradeMODX extra
 *
 * Copyright 2015-2025 Bob Ray <https://bobsguides.com>
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

class UpgradeMODXDownloadfilesProcessor extends UgmProcessor {
//    public $languageTopics = array('upgrademodx:default');

    /** @var $client GuzzleHttp\Client */
    public $client = null;
    public $sourceUrl = '';
    public $destinationPath = '';
    public $modxTimeout = 5;


    function initialize() {
        /** @var $scriptProperties array */
        parent::initialize();
        $this->name = 'Download Files Processor';
        $this->modxTimeout = $this->modx->getOption('ugm_modx_timeout', null, 6, true);
        /* Write directly because we want to truncate the file */
        $fp = fopen($this->logFilePath, 'w');
        if ($fp) {
            $d = new DateTime();
            $date = $d->format('l F d Y h:i A');
            fwrite($fp, 'UpgradeMODX Log -- ' . $date);
            fclose($fp);
        } else {
            $this->addError($this->modx->lexicon('ugm_could_not_open') . ' ' . $this->logFilePath);
        }
        $this->log($this->modx->lexicon('ugm_downloading_files'));

        $v = (int)$this->modx->getVersionData()['version'];

        if ($v >= 3) {
            $path = $this->modx->getOption('core_path', null);

        } else {
            $path = MODX_CORE_PATH . 'components/upgrademodx/';
        }
        require_once $path . 'vendor/autoload.php';

        $version = $this->zipFileName;
        $this->log("Version: " . $version);
        $v = explode('-', $version);
        $shortVersion = $v[1];
        $_SESSION['ugm_version'] = $shortVersion;
        // Example: https://modx.s3.amazonaws.com/releases/2.6.5/modx-2.6.5-pl.zip
        $this->sourceUrl = 'https://modx.s3.amazonaws.com/releases/' . $shortVersion . '/' . $version;
        $this->log("URL: " . $this->sourceUrl);
        // return;
        $this->destinationPath = $this->tempDir . $this->zipFileName;
        $this->client = new Client();
        return true;
    }


    function remoteFileExists() : bool {
        /** @var $client GuzzleHttp\Client */
        $client = $this->client;

        try {
            $client->head($this->sourceUrl);
            return true;
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return false;
        }
    }

    /** @throws Exception
     * @throws GuzzleException
     */

    public function download() : void {

        /* See if the file is available for download */
        if (!$this->remoteFileExists()) {
            throw new Exception($this->modx->lexicon('ugm_no_such_version'));
        }

        $client = $this->client;
        $destFile = fopen($this->destinationPath, 'w');
        if (!$destFile) {
            $msg = '[Download Files Processor] ' .
                $this->modx->lexicon('ugm_could_not_open') . ' ' .
                $this->destinationPath . ' ' . $this->modx->lexicon('ugm_for_writing');
            throw new Exception($msg);

        }

        set_time_limit(0);
        $options = array(
            RequestOptions::SINK => $destFile, // the body of a response
            RequestOptions::CONNECT_TIMEOUT => $this->modxTimeout,    // request
            RequestOptions::VERIFY => (bool)$this->sslVerifyPeer,
            // RequestOptions::TIMEOUT => 0.0,    // response
        );

        if (!empty($this->certPath)) {
            $options[RequestOptions::CERT] = $this->certPath;
        }

        // $options = array();
        $options['headers'] = array(
            'Cache-Control' => 'no-cache',
            'Accept' => 'application/zip'
        );

        try {
            $response = $client->request('GET', $this->sourceUrl, $options);
        } catch (Exception $e) {
            fclose($destFile);
            unlink($this->destinationPath);
            throw new exception($this->modx->lexicon('ugm_download_failed') . ' -- ' . $e->getMessage());
        }

        fclose($destFile);

        if (filesize($this->destinationPath) === 0) {
            throw new exception($this->modx->lexicon('ugm_download_failed')
                . ' Filesize: 0');
        } else {
            $msg = $this->modx->lexicon('ugm_downloaded') . ' ' . $_SESSION['ugm_version'] .
                ' -> ' . $this->destinationPath;
            $this->log($msg);
        }
    }

    /**
     * @return array|mixed|string
     * @throws GuzzleException
     */
    public function process() {
        if ((!$this->devMode) || (!file_exists($this->tempDir . $this->zipFileName))) {
            try {
                $this->download();
            } catch (Exception $e) {
                $this->addError($e->getMessage());
            }
        }
        /* message for next processor */

        return $this->prepareResponse($this->modx->lexicon('ugm_unzipping_files'));
    }
}

return 'UpgradeMODXDownloadfilesProcessor';
