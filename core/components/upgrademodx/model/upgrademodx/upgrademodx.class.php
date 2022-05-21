<?php

use GuzzleHttp\Exception\RequestException;

/**
 * UpgradeMODX class file for UpgradeMODX Widget snippet for  extra
 *
 * Copyright 2015-2022 Bob Ray <https://bobsguides.com>
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
    /**
     * Class UpgradeMODX
     */
    class UpgradeMODX {

        /** @var $versionArray array - array of versions to display if upgrade is available as a string
         *  to inject into upgrade script */
        public $versionArray = '';

        /** @var $renderedVersionList string - Rendered version list to use in createVersionForm */
        public $renderedVersionList = '';

        /** @var $versionListPath string - location of versionlist file */
        public $versionListPath;

        /** @var $modx modX - modx object */
        public $modx = null;

        /** @var $latestVersion string - latest version available */
        public $latestVersion = '';

        /** @var $errors array - array of error message (non-fatal errors only) */
        public $errors = array();

        /** @var $forcePclZip boolean */
        public $forcePclZip = false;

        /** @var $githubTimeout int */
        public $gitHubTimeout = 6;

        /** @var $modxTimeout int */
        public $modxTimeout = 6;

        /** @var $verifyPeer int */
        public $verifyPeer = true;

        /** @var $github_username string */
        public $github_username = '';

        /** @var $github_token string */
        public $github_token = '';

        /** @var $devMode bool */
        protected $devMode = false;

        /** @var $versionsToShow bool */
        protected $versionsToShow = 5;

        /** @var $progressFilePath string */
        protected $progressFilePath = '';

        /** @var $progressFileURL string */
        protected $progressFileURL = '';

        /** @var $client GuzzleHttp\Client */
        public $client = null;

        /** @var $corePath string */
        public $corePath = '';

        /** @var $plOnly bool */
        protected $plOnly = false;

        /** @var $showMODX3 bool */
        protected $showMODX3 = false;

        /** @var $certPath string */
        protected $certPath = '';

        /** @var $githubUrl string */
        protected $githubUrl = '';

        /** @var string $fileVersion - latest version when versionlist file last created */
        protected $fileVersion = '';

        /** @var $verbose bool */
        protected $verbose = false;

        /** @var $isMODX3 bool */
        protected $isMODX3 = false;

        /** @var $lastCheck string  */
        protected $lastCheck = '2015-08-17 00:00:004';


        /**
         * UpgradeMODX constructor.
         * @param $modx modX
         */
        public function __construct($modx) {
            /** @var $modx modX */
            $this->modx =& $modx;
        }

        /**
         * Initialize variables and do some setup
         */
        public function init() {
            /** @var $InstallData array */
            $this->createModxErrorLog();
            $this->devMode = (bool)$this->modx->getOption('ugm.devMode', null, false, true);
            $this->verbose = (bool)$this->modx->getOption('ugm_verbose', null, false, true);
            $language = $this->modx->getOption('ugm_language',
                null, $this->modx->getOption('manager_language'), true);
            $language = empty($language) ? 'en' : $language;
            $this->modx->lexicon->load($language .
                ':upgrademodx:default');
            $this->forcePclZip = $this->modx->getOption(
                'ugm_force_pcl_zip', null, false, true);
            $this->plOnly = $this->modx->getOption(
                'ugm_pl_only', null, true, true);
            $this->showMODX3 = (bool) $this->modx->getOption('ugm_show_modx3', null, false, true);
            $this->gitHubTimeout = $this->modx->getOption('ugm_github_timeout', null, 6, true);
            $this->modxTimeout = $this->modx->getOption('ugm_modx_timeout', null, 6, true);
            $this->githubUrl = $this->modx->getOption('ugm_versionlist_api_url',
                null, '//api.github.com/repos/modxcms/revolution/tags',
                true);;
            $this->errors = array();
            $this->latestVersion = $this->modx->getOption('ugm_latest_version', null, '', true);
            $this->verifyPeer = (bool)$this->modx->getOption(
                'ugm_ssl_verify_peer', null, true);
            $this->certPath = $this->modx->getOption(
                'ugm_cert_path', null, '', true
            );
            $this->versionListPath = $this->getVersionListPath(
                $this->modx->getOption(
                    'ugm_version_list_path', null),
                    $this->devMode
            );
            $this->progressFilePath = MODX_ASSETS_PATH .
                'components/upgrademodx/ugmprogress.txt';
            $this->mmkDir(MODX_ASSETS_PATH . 'components/upgrademodx');
            $this->progressFileURL = MODX_ASSETS_URL .
                'components/upgrademodx/ugmprogress.txt';
            $this->fileVersion = $this->modx->getOption(
                'ugm_file_version', null, '', true
            );
            file_put_contents($this->progressFilePath,
                'Starting Upgrade');
            $this->versionsToShow = $this->modx->getOption(
                'ugm_versions_to_show', null, 5, true
            );

            $v = (int) $this->modx->getVersionData()['version'];

            if ($v >= 3) {
                $this->isMODX3 = true;
                $path = $this->modx->getOption('core_path', null);
            } else {
                $this->isMODX3 = false;
                $path = MODX_CORE_PATH . 'components/upgrademodx/';
            }
            require_once $path . 'vendor/autoload.php';

            /* These use System Setting if property is empty */
            $this->setGithubCredentials();
            $this->client = new \GuzzleHttp\Client();
            $this->mmkDir($this->versionListPath);

            /* For unit tests */
            $this->corePath = $this->modx->getOption('ugm.core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/upgrademodx/');
        }

        /**
         *  Make sure MODX error log file exists in
         *  case PHP errors are thrown after a manual
         *  cache clear
         */
        public function createModxErrorLog() {
            $eLogPath = $this->modx->getCachePath() . 'logs/';
            if (!is_dir($eLogPath)) {
                $this->mmkDir($eLogPath);
            }
            $file = $eLogPath . 'error.log';
            if (!file_exists($file)) {
                touch($file);
            }
        }

        /**
         * Set GitHub credential class variables
         */
        public function setGithubCredentials() {
            $this->github_username = $this->modx->getOption(
                'ugm_github_username', null, null, true);
            $this->github_token = $this->modx->getOption(
                'ugm_github_token', null, null, true);
        }

        /**
         * Set versionlist path from setting or (if default)
         * set it using cachePath from CacheManager
         * or local dummy/ugm temp if ugm.devMode is on.
         *
         * @param $path --
         * @param bool $devMode
         * @return string
         */
        public function getVersionListPath($path, $devMode = false) {
            if ($devMode) {
                return ('c:/dummy/ugmtemp/');
            }
            /* If path is empty or contains hard-coded default cache path,
               get true path from cacheManager */
            if (($path === '') || (stripos($path, 'core/cache/upgrademodx/') !== false)) {
                $cm = $this->modx->getCacheManager();
                $path = $cm->getCachePath() . 'upgrademodx/';
            }
            $this->mmkDir($path);
            $this->versionListPath = $path;
            return $path;
        }

        /**
         * @return bool - true if file exists, false if not
         */
        public function versionListExists() {
            return file_exists($this->versionListPath . 'versionlist');
        }

        /**
         * Create final version form that will be shown
         * in the Manager. Uses rawVersions from versionlist file,
         * calls finalizeVersionArray to create sorted array with
         * current version and recommended version flagged, then calls
         * renderVersionList to render the versionlist part of the form.
         * @param $modx
         * @return bool|string -- returns form or false on error
         */
        public function createVersionForm($modx) {
            $path = $this->versionListPath . 'versionlist';
            if (!file_exists($path)) {
                $this->setError($this->modx->lexicon('ugm_no_version_list') . ' @ ' . $path);
                return false;
            }
            $rawVersions = file_get_contents($path);
            if (empty($rawVersions)) {
                $this->setError($this->modx->lexicon('ugm_no_version_list') . ' @ ' . $path);
                return false;
            }

            $finalVersionList = $this->finalizeVersionArray(
                $rawVersions,
                $this->plOnly,
                $this->versionsToShow
            );
            if (!is_array($finalVersionList)) {
                return false;
            }
            $renderedVersionList =
                $this->renderVersionList($finalVersionList);


            /** @var $upgrade  UpgradeMODX */
            /** @var $modx modX */
            $output = '';
            $output .= "\n" . '<div id="upgrade_form">';
            $output .= "\n<p>" . $modx->lexicon('ugm_get_major_versions') . '</p>';
            $output .= "\n" . '</div>' . "\n ";
            $output .= $renderedVersionList;
            if (stripos($output, 'Error') === false) {
                $output .= "\n" . $this->getButtonCode($modx->lexicon('ugm_begin_upgrade'));
            }
            return $output;
        }

        /**
         * Gets Internet Explorer version.
         * Used to adjust JS and submit button
         * @return bool
         */
        public static function getIeVersion() {
            $version = false;
            preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
            if (count($matches) < 2) {
                preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
            }

            if (count($matches) > 1) {
                // We're using IE < version 12 (Edge)
                $version = $matches[1];
            }

            return $version;
        }

        /**
         * Generates button code based on Browser and IE version
         *
         * @param string $action
         * @param bool $disabled
         * @param bool $submitted
         * @return string
         */
        public function getButtonCode($action = "[[+ugm_begin_upgrade]]", $disabled = false, $submitted = false) {
            $disabled = $disabled ? ' disabled ' : '';
            $red = $submitted ? ' red' : '';
            $ie = $this->getIeVersion();
            $buttonCode = '';

            if ($ie) {
                $buttonCode .= '
        <button type="submit" name="IeSucks" id="ugm_submit_button" class="progress-button' . $red . '" data-style="fill"
                data-horizontal' . $disabled . '>' . $action . '</button>';
            } else {
                $buttonCode = '
        <button type = "submit" name="IeSucks" id="ugm_submit_button" class="progress-button' . $red . '" data-style="rotate-angle-bottom" data-perspective
                data-horizontal' . $disabled . '>' . $action . '</button>';
            }

            return $buttonCode;
        }


        /**
         * Creates final version array from raw versions,
         * with current and recommended version flagged
         * Final arg is there for unit tests
         * @param $contents
         * @param bool $plOnly
         * @param int $versionsToShow
         * @param string $currentVersion
         * @return array|bool -- returns version array or false on error
         */
        public function finalizeVersionArray(
            $contents,
            $plOnly = true,
            $versionsToShow = 5,
            $currentVersion = ''
            ) {
            $currentVersion = empty($currentVersion)
                ? $this->modx->getOption('settings_version', null)
                : $currentVersion;
            // $contents = utf8_encode($contents);
            $contents = $this->modx->fromJSON($contents);
            if (empty($contents)) {
                $this->setError($this->modx->lexicon('ugm_json_decode_failed'));
                return false;
            }


            /* remove non-pl version objects if plOnly is set, and remove MODX 2.5.3;
            Remove MODX 3 versions on MODX 2 sites unless ugm_show_modx3 system setting is set. */
            foreach ($contents as $key => $content) {
                /* remove initial 'V' */
                $name = substr($content['name'], 1);

                if ((int) $name[0] === 3 && (int) $currentVersion[0] < 3 && (!$this->showMODX3)) {
                    unset($contents[$key]);
                    continue;
                }
                if ($plOnly && strpos($name, 'pl') === false) {
                    unset($contents[$key]);
                    continue;
                }
                if (strpos($name, '2.5.3-pl') !== false) {
                    unset($contents[$key]);
                }
            }
            $contents = array_values($contents); // 'reindex' array


            /* GitHub won't necessarily have them in the correct order.
               Sort them with a Custom insertion sort since they will
               be almost sorted already */

            /* Make sure we don't access an invalid index */
            $versionsToShow = min($versionsToShow, count($contents));

            /* Make sure we show at least one */
            $versionsToShow = !empty($versionsToShow) ? $versionsToShow : 1;

            /* Sort by version */
            $count = count($contents);
            for ($i = 0; $i < $count; $i++) {
                $element = $contents[$i];
                $j = $i;
                while ($j > 0 && (version_compare($contents[$j - 1]['name'], $element['name']) < 0)) {
                    $contents[$j] = $contents[$j - 1];
                    $j = $j - 1;
                }
                $contents[$j] = $element;
            }

            /* Truncate to $versionsToShow but extend to show current version plus one previous version */

            $versionArray = array();
            $i = 1;
            $currentFound = false;
            foreach ($contents as $version) {
                $name = substr($version['name'], 1);
                $compare = version_compare($currentVersion, $name);

                $shortVersion = strtok($name, '-');
                $url = 'https://modx.s3.amazonaws.com/releases/' . $shortVersion . '/modx-' . $name . '.zip';
                // $url = 'https://modx.com/download/direct?id=modx-' . $name . '.zip'; // backup if AWS not used
                $versionArray[$name] = array(
                    'tree' => 'Revolution',
                    'name' => 'MODX Revolution ' . htmlentities($name),
                    'link' => $url,
                    'location' => 'setup/index.php',
                    'selected' => false,
                    'current' => $compare === 0 ? true : false,
                );

                if ($currentFound && ($i >= ($versionsToShow))) {
                    break;
                }

                if ($compare >= 0) {
                    $currentFound = true;
                    $i++;
                    continue;
                }
                $i++;
            }

            /* Select oldest X.X.0 version newer than current version or
              latest if there isn't one. */
            reset($versionArray);
            $latest = key($versionArray);
            $this->latestVersion = $latest;

            /* Reverse array so we can stop at the first one that
               fits the criteria */
            $versionArray = array_reverse($versionArray, true);
            $selectedOne = false;
            foreach ($versionArray as $key => $value) {

                $pattern = "/\d+\.\d+\.0/";
                /* If it's a .0 version newer than the current version, select it */
                if (preg_match($pattern, $key)) {
                    if (version_compare($key, $currentVersion) > 0) {
                        $versionArray[$key]['selected'] = true;
                        $selectedOne = true;
                        break;
                    }
                }
            }

            /* No .0 version - select latest version */
            if (!$selectedOne) {
                $versionArray[$latest]['selected'] = true;
            }

            /* Un-reverse it */
            $this->versionArray = array_reverse($versionArray, true);
            return $this->versionArray;
        }

        /**
         * Updates ugm_last_check, ugm_latest_version,
         * and ugm_file_version System Settings
         *
         * @param $lastCheck
         * @param $latestVersion
         * @param $fileVersion
         */
        public function updateSettings($lastCheck, $latestVersion, $fileVersion) {
            $settings = array(
                'ugm_last_check' => strftime('%Y-%m-%d %H:%M:%S',
                    $lastCheck),
                'ugm_latest_version' => $latestVersion,
                'ugm_file_version' => $fileVersion,
            );
            $dirty = false;
            $classPrefix = $this->isMODX3
                    ? 'MODX\Revolution\\'
                    : '';
            foreach ($settings as $key => $value) {
                if ($value === false) {
                    continue;
                }
                $setting = $this->modx->getObject($classPrefix .
                    'modSystemSetting', array('key' => $key));
                $success = true;
                if ($setting && $setting->get('value') != $value) {
                    $dirty = true;
                    $setting->set('value', $value);
                    if (!$setting->save()) {
                        $success = false;
                    }
                } else {
                    if (!$setting) {
                        $success = false;
                    }
                }
                if ($dirty) {
                    $modxVersion = $this->modx->getOption('settings_version', true);
                    $cm = $this->modx->getCacheManager();
                    if (version_compare($modxVersion, '2.1.0-pl') >= 0) {
                        $cacheRefreshOptions = array('system_settings' => array());
                        $cm->refresh($cacheRefreshOptions);
                    }
                }

                if (!$success) {
                    $msg = '[UpdateMODX.class.php] ' .
                        $this->modx->lexicon('Could not update System Setting: ' . $key);
                    $this->setError($msg);
                }
            }
        }

        /**
         * See if it's time to check for a new version based on
         * last check time and interval. Return true if ugm_last_check is empty
         *
         * @return bool true if time to check, false if not
         */
        public function timeToCheck() {
            $lastCheck = $this->modx->getOption('ugm_last_check', null, '2015-08-17 00:00:004', true);
            $this->lastCheck = $lastCheck;
            $interval = $this->modx->getOption('ugm_interval', null, '+1 day', true);
            if ($this->devMode) {
                return true;
            }
            if (empty($lastCheck)) {
                $retVal = true;
            } else {
                $interval = strpos($interval, '+') === false ? '+' . $interval : $interval;
                $retVal = time() > strtotime($lastCheck . ' ' . $interval);
            }
            return $retVal;
        }


        /**
         * Returns rendered version list for use
         * in version list part of form
         *
         * @param $versions array -- finalized version list
         * @return string
         */
        public function renderVersionList($versions) {
            $output = '';

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
                \n<label><input id="$version" type="radio"{$selected} name="modx" value="$version">
                <span>{$itemInfo['name']} $current</span>
                </label>
EOD;
                    $i++;
                } // end inner foreach loop
            } // end outer foreach loop
            $output .= "\n</div>";

            return $output;
        }


        /**
         * * Gets raw JSON version list from GitHub
         *  Long param list is mainly for unit tests
         *
         * @param $url string -- GitHub URL
         * @param $githubTimeout int
         * @param $verifyPeer bool
         * @param null $githubUsername string
         * @param null $githubToken string
         * @param null $certPath string -- optional path to SSL cert file
         * @param bool $verbose -- show verbose error messages
         *
         *
         * @return mixed returns JSON version list as string or false on failure
         * @throws \GuzzleHttp\Exception\ClientException
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function getRawVersions(
            $url,
            $githubTimeout = 6,
            $verifyPeer = true,
            $githubUsername = null,
            $githubToken = null,
            $certPath = null, $verbose = false)  {

            $options = array();
            if ((!empty($githubUsername)) && (!empty($githubToken))) { // use token if set
                $options['auth'] = array($githubUsername, $githubToken);
            }
            $options['header'] = array(
                'Cache-Control' => 'no-cache',
                'Accept' => 'application/json',
            );
            if (!empty ($certPath)) {
                $options['cert'] = $certPath;
            }

            $options['timeout'] = $githubTimeout;

            if ($verifyPeer !== true) {
                $options['verify'] = false;
            }

            try {
                $response = $this->client->request('GET', $url, $options);
                $retVal = $response->getBody();
                /* Simulate SSL error */

                //  } catch (\Exception $e) {
            } catch (RequestException $e) {
                $msg = $this->parseException($e, $verbose);
                echo $msg;
                $retVal = false;
            } catch (\Exception $e) {
                /** @var $e \GuzzleHttp\Exception\RequestException */
                $msg = $this->parseException($e, $verbose);
                echo $msg;
                $retVal = false;
            }
            return $retVal;
        }

        /**
         * Creates beautified error message based on
         * exception thrown
         *
         * @param $e GuzzleHttp\Exception\RequestException
         * @return string - Error message based on Exception
         *
         */
        public function parseException($e, $verbose = false) {
            /** @var  $response \Psr\Http\Message\MessageInterface */
            $msg = $e->getMessage();
            $prefix = $this->modx->lexicon('ugm_no_version_list_from_github') . ' -- ';
            $retVal = $msg; // default to entire message;
            $code = $e->getCode();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $rawMessage = $response->getBody();
                $message = json_decode((string)$rawMessage);
                // $x = print_r($message, true);
                if (empty($message)) {
                    $message = $response->getReasonPhrase();
                    $retVal = $code . ' ' . $message;
                } else {
                    $ex = (array)$message;
                    $retVal = $code . ' ' . $ex['message'];
                }
            } elseif (empty($code) || ($code >= 500)) {
                $code = ((int)$code === 0) ? 'No Code Returned' : $code;
                $retVal = $code . ' ' . 'Connection error (no internet?) -- Try turning off News and Security feeds.';
            }
            $retVal = $verbose ? $prefix . ' ' . $msg : $prefix . $retVal;
            $this->setError($retVal);
            return $retVal;
        }

        /**
         * Clears error array
         */
        public function clearErrors() {
            $this->errors = array();
        }

        /**
         * returns $this->latestVersion
         * @return string
         */
        public function getLatestVersion() {
            return $this->latestVersion;
        }

        /**
         * Adds error to errors array
         * @param $msg string
         */
        public function setError($msg) {
            $this->errors[] = $msg;
        }

        /**
         * returns error array
         *
         * @return array
         */
        public function getErrors() {
            return $this->errors;
        }

        /**
         * Sets $this->latestVersion based on
         * $plOnly setting and raw versions JSON string
         * @param $rawVersions string (JSON)
         * @param bool $plOnly
         */
        public function setLatestVersion($rawVersions, $plOnly = true) {
            if ($plOnly) {
                $pattern = '/name":\s*"v([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2}-pl)"/';
            } else {
                $pattern = '/name":\s*"v([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2}-[a-zA-Z0-9]+)"/';
            }
            /* Adjust pattern to grab only MODX 2 versions */
            if ($this->showMODX3 == false) {
                $pattern = str_replace('v([0-9]{1,2}', 'v(2', $pattern);
            }
            preg_match($pattern, $rawVersions, $matches);
            if (!isset($matches[1]) || empty($matches[1])) {
                $this->setError('Regex failed');
                // $this->modx->log(modX::LOG_LEVEL_ERROR, "Raw Versions: " . print_r($rawVersions, true));
            } else {
                $this->latestVersion = $matches[1];
            }
        }

        /**
         * @param $settingsVersion string - current version from System Setting
         * @param $regenerate bool - forces check with GitHub if true
         * @return bool - returns true if upgrade is available or false if not
         * @throws GuzzleHttp\Exception\GuzzleException
         * @throws \Exception
         */
        public function upgradeAvailable($settingsVersion, $regenerate = false) {
            $versionListExists = $this->versionListExists();
            $timeToCheck = $this->timeToCheck();

            /* Perform check if no latestVersion, or if it's time to check */
            if ((!$versionListExists) || $timeToCheck ||
                empty($this->latestVersion) || $regenerate) {

                $rawVersions = $this->getRawVersions(
                    $this->githubUrl,
                    $this->gitHubTimeout,
                    $this->verifyPeer,
                    $this->github_username,
                    $this->github_token,
                    $this->certPath,
                    $this->verbose
                );
                // $this->modx->log(modX::LOG_LEVEL_ERROR, "rawVersions from GitHub");

                $this->setLatestVersion($rawVersions, $this->plOnly);
                $this->updateVersionListFile(
                    $rawVersions,
                    $this->fileVersion,
                    $this->latestVersion
                );
                $this->updateSettings(
                    time(),
                    $this->latestVersion,
                    $this->latestVersion
                );
            } else { /* Not time to check and versionlist file exists */
                $rawVersions = file_get_contents($this->versionListPath . 'versionlist');

               // $this->modx->log(modX::LOG_LEVEL_ERROR, "rawVersions from versionlist");

                $this->setLatestVersion($rawVersions, $this->plOnly);
                $this->updateSettings(strtotime($this->lastCheck),
                    $this->latestVersion, $this->latestVersion);
            }

            if (!empty($this->errors)) {
                $upgradeAvailable = false;
            } else {
                /* See if the latest version is newer than the current version */
                $upgradeAvailable = version_compare($settingsVersion, $this->latestVersion) < 0;
            }
            return $upgradeAvailable;
        }


        /**
         * Updates versionlist file with raw JSON version string
         * only if it's not already up to date. Sets an error
         * if versionlist file can't be opened for writing
         *
         * @param $rawVersions - JSON string with raw versionlist
         * @param $fileVersion -- latest version in file when last saved
         * @param $latestVersion
         */
        public function updateVersionListFile($rawVersions, $fileVersion, $latestVersion) {
            $path = $this->versionListPath;
            if ($fileVersion == $latestVersion && $this->versionListExists()) {
                return;
            }

            $this->mmkDir($path);
            file_put_contents($path . 'versionlist', $rawVersions);


            $fp = @fopen($this->versionListPath . 'versionlist', 'w');
            if ($fp) {
                fwrite($fp, $rawVersions);
                fclose($fp);
            } else {
                $this->setError($this->modx->lexicon('ugm_could_not_open') .
                    ' ' . $path . 'versionlist ' . ' ' .
                    $this->modx->lexicon('ugm_for_writing'));
            }
        }

        /**
         * Creates directory if it doesn't exist
         *
         * @param $folder - path to directory
         * @param int $perm - folder permissions
         */
        public function mmkDir($folder, $perm = 0755) {
            if (!is_dir($folder)) {
                mkdir($folder, $perm, true);
            }
        }
    }
}
