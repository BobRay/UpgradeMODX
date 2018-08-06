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


class GetVersionsProcessor extends modProcessor {

    public $languageTopics = array('upgrademodx:default');
    public $corePath;
    /** @var $upgrade UpgradeMODX */
    public $upgrade; /* UpgradeMODX class */
    public $method = 'curl';
    protected $username = '';
    protected $token = '';
    protected $client = null;

    function initialize() {
        parent::initialize();
        $this->username = $this->modx->getOption('github_username');
        $this->token = $this->modx->getOption('github_token');

        $corePath = $this->modx->getOption('ugm.core_path', null, $this->modx->getOption('core_path') . 'components/upgrademodx/');
        require_once $corePath . 'model/upgrademodx/upgrademodx.class.php';
        require_once $corePath . 'vendor/autoload.php';
        $this->client = new Client([
            'base_uri' => 'https://api.github.com',
        ]);
        $this->corePath = $corePath;
        $this->upgrade = new UpgradeMODX($this->modx);
        return true;
    }

    public function getJSONFromGitHub($method) {
        // $header = "Authorization: Basic " . base64_encode($username . ':' . $token);
        if ( (! empty($this->username)) && (!empty($this->token)) ) {
            $header = array('auth' => array($this->username, $this->token));
            $response = $this->client->request('GET', 'repos/modxcms/revolution/tags', $header);
        } else {
            $response = $this->client->request('GET', 'repos/modxcms/revolution/tags');
        }
        return $response->getBody();
    }


    public function createVersionList($method) {
        $output = '';
        $versions = $this->getJSONFromGitHub($method);
        $versions = $this->upgrade->finalizeVersionArray($versions);

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
        return $output . "\n</div>" ;
    }

    public function process() {

        $o = $this->createVersionList($this->method);

        /* perform action here */

        //  $o = '<h3>Version List</h3>';


        return $this->success($o);

    }
}

return 'GetVersionsProcessor';
