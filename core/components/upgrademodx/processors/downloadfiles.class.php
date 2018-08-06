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
        $props = $this->props;
        $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($this->props, true));
        $corePath = $this->modx->getOption('ugm.core_path', null, $this->modx->getOption('core_path') . 'components/upgrademodx/');
        require_once $corePath . 'vendor/autoload.php';
        $version = $this->getProperty('version', null);
        $shortVersion = strtok($version, '-');
        $this->sourceUrl = 'https://modx.s3.amazonaws.com/releases/' . $shortVersion . '/modx-' . $version . '.zip';
        $this->destinationPath = 'c:/dummy/mymodx.zip'; // ToDo: get this from System Setting
        $this->client = new Client();
        if ($this->devMode) {
            $this->sourceUrl = 'http://localhost/addons/sitecheck.zip';
            $this->destinationPath = 'c:/dummy/downloaded_file.zip';
        }
        return true;
    }

    public function download() {
        $client = new Client();

        $destFile = fopen($this->destinationPath, 'wb');
        set_time_limit(0);
        try {
            $response = $client->request('GET', $this->sourceUrl, [
                'headers' => array(
                    'Cache-Control' => 'no-cache',
                    'Accept' => 'application/zip'
                ),
                'sink' => $destFile,
            ]);

        } catch (RequestException $e) {

            // If there are network errors, we need to ensure the application doesn't crash.
            // if $e->hasResponse is not null we can attempt to get the message
            // Otherwise, we'll just pass a network unavailable message.
            if ($e->hasResponse()) {
                $exception = (string)$e->getResponse()->getBody();

                echo $exception;
                // $exception = json_decode($exception);
                // return new JsonResponse($exception, $e->getCode());
                echo "\n" . $e->getCode();
            } else {
                // return new JsonResponse($e->getMessage(), 503);
                echo $e->getMessage();
            }

        } catch (Exception $e) {
            if ($e->hasResponse()) {
                $exception = (string)$e->getResponse()->getBody();

                echo $exception;
                // $exception = json_decode($exception);
                // return new JsonResponse($exception, $e->getCode());
                echo "\nCODE: " . $e->getCode();
            } else {
                // return new JsonResponse($e->getMessage(), 503);
                echo "\nMESSAGE: " . $e->getMessage();
            }

        } catch (exception $e) {

        }
    }

    public function exists() {
        $client = new GuzzleHttp\Client;

        try {
            $client->head($this->sourceUrl);
            return true;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        }
    }
    public function process() {

       //  $result = $this->download();

     if ($this->exists()) {
         $this->modx->log(modX::LOG_LEVEL_ERROR, 'FILE EXISTS');
     } else {
         $this->modx->log(modX::LOG_LEVEL_ERROR, 'FILE NOT THERE');
     }

        return $this->success($this->modx->lexicon('ugm_unzipping_files'));

    }
}

return 'UpgradeMODXDownloadfilesProcessor';
