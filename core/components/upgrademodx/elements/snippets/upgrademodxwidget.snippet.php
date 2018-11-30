<?php
/**
 * UpgradeMODXWidget snippet for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
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

if (php_sapi_name() === 'cli') {
    /* This section for debugging during development. It won't execute in MODX */
/*    include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';
    $snippet =
    $scriptProperties = array(
        'versionsToShow' => 5,
        'hideWhenNoUpgrade' => false,
        'lastCheck' => '',
        'interval' => '+60 seconds',
        'plOnly' => false,
        'language' => 'en',
        'forcePclZip' => false,
        'currentVersion' => $modx->getOption('settings_version'),
        'latestVersion' => '2.4.3-pl',
        'githubTimeout' => 6,
        'modxTimeout' => 6,
    );*/

}

/* Initialize */
/* This will execute when in MODX */
$language = $modx->getOption('ugm_language', null, $modx->getOption('manager_language'), true);
$language = empty($language) ? 'en' : $language;
$props = $scriptProperties;
$modx->lexicon->load($language . ':upgrademodx:default');
/* Return empty string if user shouldn't see widget */
$devMode = $modx->getOption('ugm.devMode', null, false, true);
$groups = $modx->getOption('ugm_groups', null, 'Administrator', true);

if (strpos($groups, ',') !== false) {
    $groups = explode(',', $groups);
}
if (! $modx->user->isMember($groups)) {
    return '';
}

$corePath = $modx->getOption('ugm.core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/upgrademodx/');
$assetsUrl = $modx->getOption('ugm.assets_url', null, $modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/upgrademodx/');
require_once($corePath . 'model/upgrademodx/upgrademodx.class.php');
$upgrade = new UpgradeMODX($modx);
$upgrade->init($props);
$props['ugm_setup_url'] = MODX_SITE_URL . 'setup/index.php';
unset($props['controller']); // remove trash from scriptProperties
$modx->regClientStartupScript('<script>
var ugmConnectorUrl = "' . $assetsUrl . 'connector.php";
var ugm_config = ' . $modx->toJSON($props)  . ';
var ugm_setup_url = "' . MODX_SITE_URL . 'setup/index.php";
</script>'
);
$modx->regClientCSS($assetsUrl . 'css/progress.css');
$modx->regClientStartupScript("//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
$modx->regClientStartupScript($assetsUrl . 'js/modernizr.custom.js');
$lastCheck = $modx->getOption('ugm_last_check', null, '2015-08-17 00:00:004', true);
$interval = $modx->getOption('ugm_interval', null, '+1 day', true);
$hideWhenNoUpgrade = $modx->getOption('ugm_hide_when_no_upgrade', null, false, true);
$plOnly = $modx->getOption('ugm_pl_only', null, true, true);
$versionsToShow = $modx->getOption('ugm_versions_to_show', null, 5, true);
$settingsVersion = $modx->getOption('settings_version');
$latestVersion = $modx->getOption('ugm_latest_version', null, '', true);
/* $fileVersion is current version at time of last versionlist creation */
$fileVersion = $modx->getOption('ugm_file_version', null, '', true);
$regenerate = false;
if ($fileVersion !== $settingsVersion) {
    $regenerate = true;
}

/* Set Placeholders */
$placeholders = array();
$placeholders['[[+ugm_assets_url]]'] = $assetsUrl;
$placeholders['[[+ugm_current_version]]'] = $settingsVersion;
$placeholders['[[+ugm_current_version_caption]]'] = $modx->lexicon('ugm_current_version_caption');
$placeholders['[[+ugm_latest_version_caption]]'] = $modx->lexicon('ugm_latest_version_caption');

$versionListExists = $upgrade->versionListExists();

$timeToCheck = $upgrade->timeToCheck($lastCheck, $interval);

/* Perform check if no latestVersion, or if it's time to check, or settings_version has been changed */
if ((!$versionListExists ) || $timeToCheck || empty($latestVersion) || $regenerate) {
    $upgradeAvailable = $upgrade->upgradeAvailable($settingsVersion);
    $latestVersion = $upgrade->getLatestVersion();
} else {
    $upgradeAvailable = version_compare($settingsVersion, $latestVersion) < 0;
}

$placeholders['[[+ugm_latest_version]]'] = $latestVersion;

if ($devMode) {
    $upgradeAvailable = true;
}

if ($upgradeAvailable) {
    $versionForm = $upgrade->createVersionForm($modx);
}

$errors = $upgrade->getErrors();

if (!empty($errors)) {
    $msg = '';
    foreach ($errors as $error) {
        $msg .= '<br/><span style="color:red">' . $modx->lexicon('ugm_error') .
            ': ' . $error . '</span>';
    }

    return $msg;
}

/* Process */

/* See if there's a new version */
if ($upgradeAvailable) {
    $placeholders['[[+ugm_notice]]'] = $modx->lexicon('ugm_upgrade_available');
    $placeholders['[[+ugm_notice_color]]'] = 'green';
    $placeholders['[[+ugm_version_form]]'] = $versionForm;
} else {
    if ($hideWhenNoUpgrade) {
        return '';
    } else {
        $placeholders['[[+ugm_notice]]'] = $modx->lexicon('ugm_modx_up_to_date');
        $placeholders['[[+ugm_notice_color]]'] = 'gray';
    }
}
/* Get Tpl */
$tpl = $modx->getChunk('UpgradeMODXTpl');

/* Do the replacements */
$tpl = str_replace(array_keys($placeholders), array_values($placeholders), $tpl);

/*if (php_sapi_name() === 'cli') {
    echo $tpl;
}*/

return $tpl;