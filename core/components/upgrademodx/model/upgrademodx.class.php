<?php
/**
 * UpgradeMODX class file for UpgradeMODX Widget snippet for  extra
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
 * Created on 08-16-2015
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
 */

/**
 * Description
 * -----------
 * UpgradeMODX Dashboard widget
 * This package was inspired by the work of a number of people and I have borrowed some of their code.
 * Dmytro Lukianenko (dmi3yy) is the original author of the MODX install script. Susan Sottwell, Sharapov,
 * Bumkaka, Inreti, Zaigham Rana, frischnetz, and AgelxNash, also contributed and I'd like to thank all
 * of them for laying the groundwork.
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/

/* Properties

 * @property &groups textfield -- group, or commma-separated list of groups, who will see the widget; Default: (empty)..
 * @property &hideWhenNoUpgrade combo-boolean -- Hide widget when no upgrade is available; Default: No.
 * @property &interval textfield -- Interval between checks -- Examples: 1 week, 3 days, 6 hours; Default: 1 week.
 * @property &language textfield -- Two-letter code of language to user; Default: en.
 * @property &lastCheck textfield -- Date and time of last check -- set automatically; Default: (empty)..
 * @property &latestVersion textfield -- Latest version (at last check) -- set automatically; Default: (empty)..
 * @property &plOnly combo-boolean -- Show only pl (stable) versions; Default: yes.
 * @property &versionsToShow textfield -- Number of versions to show in upgrade form (not widget); Default: 5.

 */
if (!class_exists('UpgradeMODX')) {
    class UpgradeMODX {

        /** @var $versionlist string - array of versions to display if upgrade is available as a string
         *  to inject into upgrade script */
        public $versionList = '';

        /** @var $versionArray string - array of versions to display if upgrade is available as a string
         *  to inject into upgrade script */

        public $versionArray = '';

        /** @var $modx modX - modx object */
        public $modx = null;

        /** @var $latestVersion string - latest version available; set only if an upgrade */
        public $latestVersion = '';

        /** @var $currentVersion string - current version in use */
        public $currentVersion = '';

        /** @var $errors array - array of error message (non-fatal errors only) */
        public $errors = array();

        /** @var $forcePclZip boolean */
        public $forcePclZip = false;

        /** @var $forceFopen boolean */
        public $forceFopen = false;

        /** @var $plOnly boolean */
        public $plOnly = false;

        /** @var $versionsToShow int */
        public $versionsToShow = false;

        /** @var $githubTimeout int */
        public $gitHubTimeout = 6;

        /** @var $modxTimeout int */
        public $modxTimeout = 6;


        public function __construct($modx) {
            /** @var $modx modX */
            $this->modx = $modx;
        }

        public function init($props) {
            /** @var $InstallData array */
            $language = $this->modx->getOption('language', $props, 'en', true);
            $this->modx->lexicon->load($language . ':upgrademods:default');
            $this->forcePclZip = $this->modx->getOption('forcePclZip', $props, false);
            $this->forceFopen = $this->modx->getOption('forceFopen', $props, false);
            $this->plOnly = $this->modx->getOption('plOnly', $props);
            $this->versionsToShow = $this->modx->getOption('versionsToShow', $props, 5);
            $this->gitHubTimeout = $this->modx->getOption('githubTimeout', $props, 6, true);
            $this->modxTimeout = $this->modx->getOption('modxTimeout', $props, 6, true);
            $this->currentVersion = $this->modx->getOption('settings_version');
            $this->errors = array();

            $path = MODX_CORE_PATH . 'cache/upgrademodx/versionlist';
            if (!file_exists($path)) {
                $retVal = $this->getJSONFromGitHub($this->gitHubTimeout,$this->forceFopen);
                if ($retVal !== false) {
                    $retVal = $this->finalizeVersionArray($retVal);
                    if ($retVal !== false) {
                        $this->updateVersionlistFile($retVal);
                        $this->versionArray = $retVal;
                    }
                }
            } else {
                require $path;
                $this->versionArray = $InstallData;
            }
            $latest = reset($this->versionArray);
            $this->latestVersion = substr($latest['name'], 16);

        }

        public function writeScriptFile() {

            $fp = @fopen(MODX_BASE_PATH . 'upgrade.php', 'w');
            if ($fp) {
                $file = MODX_CORE_PATH . 'cache/upgrademodx/versionlist';
                $versionList = var_export($this->versionArray, true);

                if (empty($versionList)) {
                    $this->setError($this->modx->lexicon('ugm_no_version_list') . '@ ' . $file);
                } else {
                    $forcePclZipString = '$forcePclZip = ';
                    $forcePclZipString .= $this->forcePclZip ? 'true' : 'false';
                    $forcePclZipString .= ';';

                    $forceFopenString = '$forceFopen = ';
                    $forceFopenString .= $this->forceFopen ? 'true' : 'false';
                    $forceFopenString .= ';';

                    $fields = array(
                        '/* [[+ForcePclZip]] */' => $forcePclZipString,
                        '/* [[+ForceFopen]] */' => $forceFopenString,
                        '/* [[+InstallData]] */' => $versionList,

                    );
                    $fileContent = $this->modx->getChunk('UpgradeMODXSnippetScriptSource');
                    $fileContent = str_replace(array_keys($fields), array_values($fields), $fileContent);
                    fwrite($fp, $fileContent);
                    fclose($fp);
                }
            } else {
                $this->setError($this->modx->lexicon('ugm_could_not_open') . ' ' . MODX_BASE_PATH . 'upgrade.php' .
                ' ' .
                $this->modx->lexicon('ugm_for_writing'));
            }
        }

        public function getJSONFromGitHub($gitHubTimeout = 6, $forceFopen = false) {
            $username = $this->modx->getOption('github_username');
            $token = $this->modx->getOption('github_token');
            $url = 'https://api.github.com/repos/modxcms/revolution/tags';
            $ch = null;
            if ($forceFopen) {
                $method = 'fopen';
                ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 6.0)');
                $opts = array(
                    'http' => array(
                        'method' => 'GET',
                        'timeout' => $gitHubTimeout,
                        'max_redirects' => 1,
                        'ignore_errors' => true,

                    )
                );

                if (!empty($username) && !empty($token)) {
                    $opts['http']['header'] = "Authorization: Basic " . base64_encode($username . ':' . $token);
                }
                $ctx = stream_context_create($opts);
                $contents = @file_get_contents($url, false, $ctx);
                // $headers = $http_response_header;
            } else {
                $method = 'curl';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                // curl_setopt($ch, CURLOPT_SSLVERSION, 4);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "revolution");
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $gitHubTimeout);
                curl_setopt($ch, CURLOPT_TIMEOUT, $gitHubTimeout);
                if (!empty($username) && !empty($token)) {
                    curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $token);
                }

                $contents = @curl_exec($ch);
            }

            if (!empty($contents)) {
                $pos = strpos($contents, 'API rate limit exceeded for');
                if ($pos !== false) {
                    $this->setError('(GitHub -- ' . $method . ') ' . substr($contents, $pos, 38));
                    return false;
                }

            } else { /* Empty return */
                $msg = $this->modx->lexicon('ugm_empty_return');

                if ($method === 'curl') {
                    $s = curl_error($ch);
                    $msg = !empty($s)?  curl_error($ch) : $msg;
                }

                $this->setError('(GitHub -- ' . $method . ') ' . $msg);

                return false;
            }

            $contents = strip_tags($contents);
            return $contents;

        }

        public function finalizeVersionArray($contents) {
            $contents = utf8_encode($contents);
            $contents = $this->modx->fromJSON($contents);
            if (empty($contents)) {
                $this->setError($this->modx->lexicon('ugm_json_decode_failed'));
                return false;
            }


            if ($this->plOnly) { /* remove non-pl version objects */
                foreach ($contents as $key => $content) {
                    $name = substr($content['name'], 1);
                    if (strpos($name, 'pl') === false) {
                        unset($contents[$key]);
                    }
                }
                $contents = array_values($contents); // 'reindex' array
            }

            /* GitHub won't necessarily have them in the correct order.
               Sort them with a Custom insertion sort since they will
               be almost sorted already */

            /* Make sure we don't access an invalid index */
            $versionsToShow = min($this->versionsToShow, count($contents));
            /* Make sure we show at least one */
            $versionsToShow = !empty($versionsToShow) ? $versionsToShow : 1;
            /* Sort by version */
            for ($i = 1; $i < $versionsToShow; $i++) {
                $element = $contents[$i];
                $j = $i;
                while ($j > 0 && (version_compare($contents[$j - 1]['name'], $element['name']) < 0)) {
                    $contents[$j] = $contents[$j - 1];
                    $j = $j - 1;
                }
                $contents[$j] = $element;
            }
            /* Truncate to $versionsToShow */
            $contents = array_splice($contents, 0, $versionsToShow);
            $versionArray = array();
            $i = 1;
            foreach ($contents as $version) {
                $name = substr($version['name'], 1);

                $url = 'http://modx.com/download/direct/modx-' . $name . '.zip';
                $versionArray[$name] = array(
                    'tree' => 'Revolution',
                    'name' => 'MODX Revolution ' . htmlentities($name),
                    'link' => $url,
                    'location' => 'setup/index.php',
                );
                $i++;
                if ($i > $this->versionsToShow) {
                    break;
                }
            }
            $this->versionArray = $versionArray;
            return $this->versionArray;


        }
        public function updateVersionlistFile($versionArray) {
            $this->mmkDir(MODX_CORE_PATH . 'cache/upgrademodx');
            $InstallData = array();

            $versionList = var_export($this->versionArray, true);

            $this->mmkDir(MODX_CORE_PATH . 'cache/upgrademodx');
            $fp = @fopen(MODX_CORE_PATH . 'cache/upgrademodx/versionlist', 'w');
            if ($fp) {
                fwrite($fp, '<' . '?p' . "hp\n" . '$InstallData = ' . $versionList . ';');
                fclose($fp);
            } else {
                $this->setError($this->modx->lexicon('ugm_could_not_open') .
                    ' ' . MODX_CORE_PATH . 'cache/upgrademodx/versionlist ' . ' ' .
                    $this->modx->lexicon('ugm_for_writing'));
            }

        }
        public function downloadable($version, $method = 'curl') {
            $downloadable = false;
            $downloadUrl = 'http://modx.com/download/direct/modx-' . $version . '.zip';

            if ($method == 'curl') { /* New version exists, see if it's available at modx.com/download */

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_TIMEOUT, $this->modxTimeout);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
                curl_setopt($ch, CURLOPT_URL, $downloadUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_NOBODY, true);

                $retCode = curl_exec($ch);

                if (empty($retCode) || ($retCode === false)) {
                    if ($retCode === false) {
                        $this->setError('(modx.com/download) ' . curl_error($ch));
                        return false;
                    } else {
                        $this->setError('(modx.com/download) ' .
                            $this->modx->lexicon('ugm_empty_return'));
                        return false;
                    }
                } else {

                    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $downloadable = $statusCode == 200 || $statusCode == 301 || $statusCode == 302;

                    if (!$downloadable) {
                        $this->setError('(modx -- ' . $method . ') ' . $this->modx->lexicon('ugm_not_available'));
                    }

                    curl_close($ch);


                    if ($downloadable) {
                        $this->updateDownloadableCache($version);

                    }
                }
            } else {
                $opts = array(
                    'http' => array(
                        'method' => 'GET',
                        'timeout' => $this->modxTimeout,
                        'max_redirects' => 1,
                        'ignore_errors' => true,
                    ));

                $context = stream_context_create($opts);

                $fp = @fopen($downloadUrl, 'r', false, $context);
                if ($fp) {
                    $downloadable = true;
                }
            }
            if (!$downloadable) {
                $this->setError('(modx -- ' . $method . ') ' . $this->modx->lexicon('ugm_not_available'));
            }
            return $downloadable;
        }

        public function updateDownloadableCache($version) {

        }
        /**
         * @param $lastCheck string = time of previous check
         * @param $interval - interval between checks
         * @return bool true if time to check, false if not
         */
        public function timeToCheck($lastCheck, $interval = '+1 week') {
            if (empty($lastCheck)) {
                $retVal = true;
            } else {
                $interval = strpos($interval, '+') === false ? '+' . $interval : $interval;
                $retVal = time() > strtotime($lastCheck . ' ' . $interval);
            }
            return $retVal;
        }

        public function getLatestVersion() {
            return $this->latestVersion;
        }

        public function setError($msg) {
            $this->errors[] = $msg;
        }

        public function getErrors() {
            return $this->errors;
        }
        public function getVersionList() {
            return $this->versionList;
        }


        public function upgradeAvailable($currentVersion, $plOnly = false, $versionsToShow = 5) {

            $latestVersionObj = reset($contents);
            $latestVersion = substr($latestVersionObj->name, 1);
            $this->latestVersion = $latestVersion;
            /* See if the latest version is newer than the current version */
            $newVersion = version_compare($currentVersion, $latestVersion) < 0;

            /* Update Properties if there are no cURL errors */
            $e = $this->getErrors();
            if (empty($e)) {
                $snippet = $this->modx->getObject('modSnippet', array('name' => 'UpgradeMODXWidget'));
                if ($snippet) {
                    $properties = $snippet->get('properties');
                    $properties['lastCheck']['value'] = strftime('%Y-%m-%d %H:%M:%S');
                    $properties['latestVersion']['value'] = $latestVersion;
                    $snippet->set('properties', $properties);
                    $snippet->save();
                }
            } else {
                return false;
            }

            $downloadable = false;

            $ch = curl_init();
            if ($newVersion) { /* New version exists, see if it's available at modx.com/download */
                $downloadUrl = 'http://modx.com/download/direct/modx-' . $this->latestVersion . '.zip';

                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
                curl_setopt($ch, CURLOPT_URL, $downloadUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_NOBODY, true);

                $retCode = curl_exec($ch);

                if (empty($retCode) || ($retCode === false)) {
                    if ($retCode === false) {
                        $this->setError('(modx.com/download) ' . curl_error($ch));
                        return false;
                    } else {
                        $this->setError('(modx.com/download) ' .
                            $this->modx->lexicon('ugm_empty_return'));
                        return false;
                    }
                }
                if ($retCode !== false) {
                    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $downloadable = $statusCode == 200 || $statusCode == 301 || $statusCode == 302;
                }

                curl_close($ch);


                if ($downloadable) {
                    /* Create the array of versions and save it in a cache file for the installer
                       This snippet will insert the versions into the form when creating the
                       script file from the Tpl chunk when the user submits the Upgrade form in the widget */

                    $this->updateVersionlist(true);

                }
            }

            return ($newVersion && $downloadable);
        }

        public function mmkDir($folder, $perm = 0755) {
            if (!is_dir($folder)) {
                mkdir($folder, $perm);
            }
        }
    }
}