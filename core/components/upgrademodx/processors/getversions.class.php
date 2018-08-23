<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
/**
 * Processor file for Example extra
 *
 * Copyright 2013 by Bob Ray <https://bobsguides.com>
 * Created on 06-18-2014
 *
 * Example is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Example is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Example; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package UpgradeMODX
 * @subpackage processors
 */

/* @var $modx modX */

include_once ('ugmprocessor.class.php');

class GetVersionsProcessor extends UgmProcessor {

    public $languageTopics = array('upgrademodx:default');
    /** @var $upgrade UpgradeMODX */
    public $upgrade; /* UpgradeMODX class */
    protected $username = '';
    protected $token = '';
    /** @var $client GuzzleHttp\Client */
    protected $client = null;

    protected $versionArray = array();

    function initialize() {
        parent::initialize();
        $this->name = 'Get Versions Processor';
        $this->username = $this->modx->getOption('github_username', null, '', true);
        $this->token = $this->modx->getOption('github_token', null, '', true);

        $corePath = $this->corePath;
        require_once $corePath . 'model/upgrademodx/upgrademodx.class.php';
        require_once $corePath . 'vendor/autoload.php';
        $this->client = new Client();
        $this->upgrade = new UpgradeMODX($this->modx);
        return true;
    }

    public function getVersions() {
        $url = $this->modx->getOption('ugm_versionlist_api_url', null, '//api.github.com/repos/modxcms/revolution/tags', true);
        try {
            if ((!empty($this->username)) && (!empty($this->token))) { // use token if set
                $header = array('auth' => array($this->username, $this->token));
                $response = $this->client->request('GET', $url, $header);
            } else { // no token
                $response = $this->client->request('GET', $url);
            }
        } catch (Exception $e) {
            $msg = $this->modx->lexicon('ugm_no_version_list_from_github') . ' &mdash; ' . $e->getMessage();
            $this->addError($msg);
            $response = false;
        }
        return $response;
    }


    public function createVersionList() {
        $output = '';
        $versions = $this->getVersions();
        if ($versions === false) {
            $output = false;
        } else {
            $versions = $this->upgrade->finalizeVersionArray($versions->getBody());
            $this->versionArray = $versions;
            $itemGrid = array();
            foreach ($versions as $ver => $item) {
                $itemGrid[$item['tree']][$ver] = $item;
            }
            $i = 0;
            $header = $this->modx->lexicon('ugm_choose_version');
            foreach ($itemGrid as $tree => $item) {
                $output .= "\n" . '<div class="column">';

                $output .= "\n" . '<label class="ugm_version_header"><span>' . $header . '</span></label>';

                foreach ($item as $version => $itemInfo) {
                    $selected = $itemInfo['selected'] ? ' checked' : '';
                    $current = $itemInfo['current'] ? ' &nbsp;&nbsp;(' . '[[%ugm_current_version_indicator]]' . ')' : '';
                    $i = 0;
                    $output .= <<<EOD
                    \n<label><input type="radio"{$selected} name="modx" value="$version">
                    <span>{$itemInfo['name']} $current</span>
                    </label>
EOD;
                    $i++;
                } // end inner foreach loop
            } // end outer foreach loop
            $output .= "\n</div>";
        }
        return $output;
    }

    public function process() {
        /** @var $o GuzzleHttp\Psr7\request */
        $o = $this->createVersionList($this->method);

        return $this->prepareResponse($o, $this->versionArray);

        /*if ($o !== false) {
            return $this->success($o);
        } else {
            return $this->failure('<p style="color:red">' . implode("<br>", $this->getErrors()) . '</p>');
        }*/

    }
}

return 'GetVersionsProcessor';
