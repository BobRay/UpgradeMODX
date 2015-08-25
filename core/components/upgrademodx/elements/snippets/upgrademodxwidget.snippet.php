<?php
/**
 * UpgradeMODXWidget snippet for UpgradeMODX extra
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
class UpgradeMODX
{

    /** @var $versionlist string - array of versions to display if upgrade is available as a string
     *  to inject into upgrade script */
    public $versionList = '';

    /** @var $snippetObject modSnippet - widget snippet (we need its properties) */
    protected $snippetObject = null;

    /** @var $modx modX - modx object */
    protected $modx = null;

    /** @var $latestVersion string - latest version available; set only if an upgrade */
    protected $latestVersion = '';

    /** @var $errors array - array of error message (non-fatal errors only) */
    protected $errors = array();

    /**
     *
     */

    public function __construct($modx)
    {
        $this->modx = $modx;
    }


    /**
     * @param $lastCheck string = time of previous check
     * @param $interval - interval between checks
     * @return bool true if time to check, false if not
     */
    public static function timeToCheck($lastCheck, $interval = '+1 week')
    {
        if (empty($lastCheck)) {
            $retVal = true;
        } else {
            $interval = strpos($interval, '+') === false ? '+' . $interval : $interval;
            $retVal = time() > strtotime($lastCheck . ' ' . $interval);
        }
        return $retVal;
    }

    public function getLatestVersion()
    {
        return $this->latestVersion;
    }

    public function setError($msg) {
        $this->errors[] = $msg;
    }

    public function getErrors() {
        return $this->errors;
    }


    public function upgradeAvailable($currentVersion, $plOnly = false, $versionsToShow = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/modxcms/revolution/tags');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "revolution");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3500);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        $contents = curl_exec($ch);

        if (empty($contents) || ($contents === false)) {
            if ($contents === false) {
                $this->setError('(GitHub) ' . curl_error($ch));
            } else {
                $this->setError('(GitHub) ' .
                    $this->modx->lexicon('ugm_empty_return'));
            }
        }

        $contents = json_decode($contents);


        if ($plOnly) { /* remove non-pl version objects */
            foreach ($contents as $key => $content) {
                $name = substr($content->name, 1);
                if (strpos($name, 'pl') === false) {
                    unset($contents[$key]);
                }
            }
        }

    /* GitHub won't necessarily have them in the correct order.
       Sort them with a Custom insertion sort since they will
       be almost sorted already */

    for ($i = 1; $i < $versionsToShow; $i++) {
        $element = $contents[$i];
        $j = $i;
        while ($j > 0 && version_compare($contents[$j - 1]->name, $element->name) < 0) {
            $contents[$j] = $contents[$j - 1];
            $j = $j - 1;
        }
        $contents[$j] = $element;
    }

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
        }

        $downloadable = false;

        $ch = curl_init();
        if ($newVersion) { /* New version exists, see if it's available at modx.com/download */
            $downloadUrl = 'http://modx.com/download/direct/modx-' . $this->latestVersion . '.zip';

            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
            curl_setopt($ch, CURLOPT_URL, $downloadUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_NOBODY, true);

            $retCode = curl_exec($ch);

            if (empty($retCode) || ($retCode === false)) {
                if ($retCode === false) {
                    $this->setError('(modx.com/download) ' . curl_error($ch));
                } else {
                    $this->setError('(modx.com/download) ' .
                        $this->modx->lexicon('ugm_empty_return'));
                }
            }
            if ($retCode !== false) {
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $downloadable = $statusCode == 200 || $statusCode == 301 || $statusCode == 302;
            }

            curl_close($ch);


            if ($downloadable) {
                /* Create the array of versions and save it in a $_SESSION variable for the installer
                   This snippet will insert the versions into the form when creating the
                   script file from the Tpl chunk when the user submits the Upgrade form in the widget */
                $InstallData = array();

                $i = 1;
                foreach ($contents as $content) {
                    $name = substr($content->name, 1);

                    $url = 'http://modx.com/download/direct/modx-' . $name . '.zip';
                    $InstallData[$name] = array(
                       'tree'     => 'Revolution',
                        'name'     => 'MODX Revolution ' . $name,
                        'link'     => $url,
                        'location' => 'setup/index.php',
                    );
                    $i++;
                    if ($i > $versionsToShow) {
                        break;
                    }
                }
                $versionList = var_export($InstallData, true);
                $_SESSION['versionList'] = '$InstallData = ' . $versionList . ';';
            }
        }

        return ($newVersion && $downloadable);
    }


    public function getVersionList() {
        return $this->versionList;
    }
}

/*********** Processing starts here ***********/


if (php_sapi_name() === 'cli') {
    /* This section for debugging during development. It won't execute in MODX */
    include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';
    $scriptProperties = array(
        'versionsToShow' => 5,
        'hideWhenNoUpgrade' => false,
        'lastCheck' => '',
        'interval' => '+1 seconds',
        'plOnly' => false,
        'language' => 'en',
    );
} else {
    /* This will execute when in MODX */
    $language = $modx->getOption('language', $scriptProperties, 'en');
    $modx->lexicon->load($language . ':upgrademodx:default');
    /* Return empty string if user shouldn't see widget */

    $groups = $modx->getOption('groups', $scriptProperties, 'Administrator', true);
    if (strpos($groups, ',') !== false) {
        $groups = explode(',', $groups);
    }
    if (! $modx->user->isMember($groups)) {
        return '';
    }
}
/* See if user has submitted the form. If so, create the upgrade script and launch it */
if (isset($_POST['UpgradeMODX'])) {
    $fp = fopen(MODX_BASE_PATH . 'upgrade.php', 'w');
    if ($fp) {
        if (! isset($_SESSION['versionList'])) {
            return $modx->lexicon('ugm_no_version_list');
        } else {
            $fields = array(
                'InstallData' => $_SESSION['versionList'],
            );
            $fileContent = $modx->getChunk('UpgradeMODXSnippetScriptSource', $fields);
            fwrite($fp, $fileContent);
            fclose($fp);
            $modx->runProcessor('security/flush');
            $modx->sendRedirect(MODX_BASE_URL . 'upgrade.php');
        }
    } else {
        return $modx->lexicon('ugm_could_not_open') . ' ' . MODX_BASE_PATH . 'upgrade.php' .
            ' ' .
            $modx->lexicon('ugm_for_writing');
    }
}


$upgrade = new UpgradeMODX($modx);

$props = $scriptProperties;
$lastCheck = $modx->getOption('lastCheck', $props);
$interval = $modx->getOption('interval', $props);
if (empty($lastCheck)) {
    $lastCheck = '2015-08-17 00:00:004';
}

if (!($lastCheck && $interval)) {
    return '<p style="color:red">' .
        $modx->lexicon('ugm_missing_properties')
        . '</p>';
}

$hideWhenNoUpGrade = $modx->getOption('hideWhenNoUpgrade', $props);
$plOnly = $modx->getOption('plOnly', $props);
$versionsToShow = $modx->getOption('versionsToShow', $props, 5);


$currentVersion = $modx->getOption('settings_version');

if ($upgrade::timeToCheck($lastCheck, $interval)) {
    $upgradeAvailable = $upgrade->upgradeAvailable($currentVersion, $plOnly, $versionsToShow);
    $latestVersion = $upgrade->getLatestVersion();
} else {
    $latestVersion = $modx->getOption('latestVersion', $props);
    $upgradeAvailable = version_compare($currentVersion, $latestVersion) < 0;;

}
$placeholders = array();
$placeholders['[[+ugm_current_version]]'] = $currentVersion;
$placeholders['[[+ugm_latest_version]]'] = $latestVersion;

$errors = $upgrade->getErrors();

if (!empty($errors)) {
    $msg = '';
    foreach ($errors as $error) {
        $msg .= '<br/><span style="color:red">' . $modx->lexicon('ugm_error') .
            ': ' . $error . '</span>';
    }
    return $msg;
}

$placeholders['[[+ugm_current_version_caption]]'] = $modx->lexicon('ugm_current_version_caption');
$placeholders['[[+ugm_latest_version_caption]]'] = $modx->lexicon('ugm_latest_version_caption');

/* See if there's a new version and if it's downloadable */
if ($upgradeAvailable) {
    $placeholders['[[+ugm_notice]]'] = $modx->lexicon('ugm_upgrade_available');
    $placeholders['[[+ugm_notice_color]]'] = 'green';
    $placeholders['[[+ugm_logout_note]]'] = '<br/><br/>(' .
        $modx->lexicon('ugm_logout_note')
        . ')';
    $placeholders['[[+ugm_form]]'] = '<br/><br/>
        <form method="post" action="">
            <input style="padding:3px 10px;margin-left:50px;background-color:whitesmoke;"
                   type="submit" name="UpgradeMODX" value="' . $modx->lexicon('ugm_upgrade_modx') .  '">
        </form>';

} else {
    if ($hideWhenNoUpgrade) {
        return '';
    } else {
        $placeholders['[[+ugm_notice]]'] = $modx->lexicon('ugm_modx_up_to_date');
        $placeholders['[[+ugm_notice_color]]'] = 'gray';
    }
}

/* Get Tpl */
$query = $modx->newQuery('modChunk', array(
    'name' => 'UpgradeMODXTpl',
));
$query->select('snippet');
$tpl = $modx->getValue($query->prepare());

/* Do the replacements */
$tpl = str_replace(array_keys($placeholders), array_values($placeholders), $tpl);

if (php_sapi_name() === 'cli') {
    echo $tpl;
}

return $tpl;