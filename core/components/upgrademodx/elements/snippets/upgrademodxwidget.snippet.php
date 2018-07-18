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

/** recursive remove dir function.
 *  Removes a directory and all its children */

if (! function_exists('rrmdir')) {
    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $prefix = substr($object, 0, 4);
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        $prefix = substr($object, 0, 4);
                        if ($prefix != '.git' && $prefix != '.svn') {
                            @unlink($dir . "/" . $object);
                        }
                    }
                }
            }
            reset($objects);
            $success = @rmdir($dir);
        }
    }
}

function createVersionForm($modx, $upgrade, $corePath, $method) {
    /** @var $upgrade  UpgradeMODX */
    /** @var $modx modX */
    $output = '';

    $output .= "\n" . '<div id="upgrade_form">';
    $output .= "\n" . $upgrade->getButtonCode($modx->lexicon('ugm_begin_upgrade'));
    $output .= "\n" . '<div class = "ugm_logout_note">'  .  $modx->lexicon('ugm_logout_note') . '</div >';
    $output .= "\n<p>" . $modx->lexicon('ugm_get_major_versions') . '</p>';
    // $versions = $upgrade->getJSONFromGitHub($method);
    // $versions = $upgrade->finalizeVersionArray($versions);

    // $disabled = $submitted ? true : false;

    /* If not submitted, add version list */

    if (true) {
       /* $ItemGrid = array();
        foreach ($versions as $ver => $item) {
            $versions[$item['tree']][$ver] = $item;
        }*/
        // $output .= "<p>[[%ugm_get_major_versions]]</p>";
        // $output .= '<p>Calling Processor</p>';
        // $output .= '<p>Path = ' . $corePath . 'processors/';
        $config = array(
            'processors_path' => $corePath . 'processors/', //xxx
        );
        $response = $modx->runProcessor('getversions', array(), $config);
        $output .= $response->response['message'];
        //  $i = 0;
/*        foreach ($ItemGrid as $tree => $item) {
            $output .= "\n" . '<form<div class="column">';;
            foreach ($item as $version => $itemInfo) {
                $selected = $itemInfo['selected'] ? ' checked' : '';
                $current = $itemInfo['current'] ? ' &nbsp;&nbsp;(' . '[[%ugm_current_version_indicator]]' . ')' : '';
                $i = 0;
                $output .= <<<EOD
       <label><input type="radio"{$selected} name="modx" value="$version">
            <span>{$itemInfo['name']} $current</span>
        </label>
EOD;
            $i++;
            } // end inner foreach loop

        } // end outer foreach loop  */
        // $output .= '<tbody></table>';
        // $output .= "\n    " . '<input type="hidden" name="userId" value="[[+modx.user.id]]">';
    }
    $output .= "\n" . '</div>' . "\n ";

    return $output;
}


function render() {
    // xxx
}

if (php_sapi_name() === 'cli') {
    /* This section for debugging during development. It won't execute in MODX */
    include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';
    $snippet =
    $scriptProperties = array(
        'versionsToShow' => 5,
        'hideWhenNoUpgrade' => false,
        'lastCheck' => '',
        'interval' => '+60 seconds',
        'plOnly' => false,
        'language' => 'en',
        'forcePclZip' => false,
        'forceFopen' => false,
        'currentVersion' => $modx->getOption('settings_version'),
        'latestVersion' => '2.4.3-pl',
        'githubTimeout' => 6,
        'modTimeout' => 6,
        'tries' => 2,
    );

} else {
    /* This will execute when in MODX */
    $language = $modx->getOption('language', $scriptProperties, 'en', true);
    $modx->lexicon->load($language . ':upgrademodx:default');
    /* Return empty string if user shouldn't see widget */
    $devMode = $modx->getOption('ugm.devMode',  null, false, true);
    $groups = $modx->getOption('groups', $scriptProperties, 'Administrator', true);
    if (strpos($groups, ',') !== false) {
        $groups = explode(',', $groups);
    }
    if (! $modx->user->isMember($groups)) {
        return '';
    }
}

$props = $scriptProperties;
$corePath = $modx->getOption('ugm.core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/upgrademodx/');
$assetsUrl = $modx->getOption('ugm.assets_url', null, $modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/upgrademodx/');
//$modx->log(modx::LOG_LEVEL_ERROR, "Assets URL: " . $assetsUrl);
$placeholders = array();
$placeholders['[[+ugm_assets_url]]'] = $assetsUrl;
require_once($corePath . 'model/upgrademodx/upgrademodx.class.php');


$upgrade = new UpgradeMODX($modx);
$upgrade->init($props);

$modx->regClientCSS($assetsUrl . 'css/progress.css');
/*$modx->regClientScript($assetsUrl . 'js/classie.js');
$modx->regClientScript($assetsUrl . 'js/progressButton.js');*/


/* See if user has submitted the form. If so, create the upgrade script and launch it */
if (isset($_POST['UpgradeMODX'])) {
    $upgrade->writeScriptFile();
    /* Log out all users before launching the form */
    if (! $devMode) {
        $sessionTable = $modx->getTableName('modSession');
        if ($modx->query("TRUNCATE TABLE {$sessionTable}") == false) {
            $flushed = false;
        } else {
            // $modx->user->endSession();
        }
    }
    $modx->sendRedirect(MODX_BASE_URL . 'upgrade.php');
} else {
    /* Just in case */
    if (file_exists(MODX_BASE_PATH . 'upgrade.php')) {
        unlink(MODX_BASE_PATH . 'upgrade.php');
    }
}
$modx->regClientStartupScript("//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
$modx->regClientStartupScript($assetsUrl . 'js/modernizr.custom.js');
/* Set the method */
$forceFopen = $modx->getOption('forceFopen', $props, false, true);
$method = null;
if (extension_loaded('curl') && (!$forceFopen)) {
    $method = 'curl';
} elseif (ini_get('allow_url_fopen')) {
    $method = 'fopen';
} else {
    die($this->modx->lexicon('ugm_no_curl_no_fopen'));
}

$lastCheck = $modx->getOption('lastCheck', $props, '2015-08-17 00:00:004', true);
$interval = $modx->getOption('interval', $props, '+1 week', true);
$interval = '+1 week';
$hideWhenNoUpgrade = $modx->getOption('hideWhenNoUpgrade', $props, false, true);
$plOnly = $modx->getOption('plOnly', $props);
$versionsToShow = $modx->getOption('versionsToShow', $props, 5);
$currentVersion = $modx->getOption('settings_version');
$latestVersion = $modx->getOption('latestVersion', $props, '', true);
$versionListPath = $upgrade->getVersionListPath();

$versionListExists = false;

$fullVersionListPath = $versionListPath . 'versionlist';
if (file_exists($fullVersionListPath)) {
    $v = file_get_contents($fullVersionListPath);
    if (! empty($v)) {
        $versionListExists = false;
    }
}

$fullVersionList = array();
$timeToCheck = $upgrade->timeToCheck($lastCheck, $interval);
/* Perform check if no versionlist or latestVersion, or if it's time to check */
if ((!$versionListExists ) || $timeToCheck || empty($latestVersion) || true) {
    $upgradeAvailable = $upgrade->upgradeAvailable($currentVersion, $plOnly, $versionsToShow, $method);
    $latestVersion = $upgrade->getLatestVersion();
    $fullVersionList = $upgrade->versionArray;
} else {
    $upgradeAvailable = version_compare($currentVersion, $latestVersion) < 0;;
}

if ($devMode) {
    $upgradeAvailable = true;
}

$placeholders['[[+ugm_current_version]]'] = $currentVersion;
$placeholders['[[+ugm_latest_version]]'] = $latestVersion;

$errors = $upgrade->getErrors();

if (!empty($errors)) {
    $msg = '';
    foreach ($errors as $error) {
        $msg .= '<br/><span style="color:red">' . $modx->lexicon('ugm_error') .
            ': ' . $error . '</span>';
    }

    /* attempt to delete any files created */
    rrmdir(MODX_BASE_PATH . 'ugmtemp');

    if (file_exists(MODX_BASE_PATH . 'modx.zip')) {
        @unlink(MODX_BASE_PATH . 'modx.zip');
    }
    if (file_exists(MODX_BASE_PATH . 'upgrade.php')) {
        @unlink(MODX_BASE_PATH . 'upgrade.php');
    }


    return $msg;
}

$placeholders['[[+ugm_current_version_caption]]'] = $modx->lexicon('ugm_current_version_caption');
$placeholders['[[+ugm_latest_version_caption]]'] = $modx->lexicon('ugm_latest_version_caption');

/* See if there's a new version and if it's downloadable */
if ($upgradeAvailable) {

    $placeholders['[[+ugm_notice]]'] = $modx->lexicon('ugm_upgrade_available');
    $placeholders['[[+ugm_notice_color]]'] = 'green';
   /* $placeholders['[[+ugm_logout_note]]'] = '<br/><br/>(' .
        $modx->lexicon('ugm_logout_note')
        . ')';*/
    // $output .= ''; //yyy

    $placeholders['[[+ugm_version_form]]'] = createVersionForm($modx, $upgrade, $corePath, $method) . // xx
                    
       '<script>
           var checkedBackground = \'#ffffff\';
           var originalBackground = $(\'label\').css( "background-color" );

           $(\'input[type="radio"]:checked\').parent().css("background",checkedBackground);
                        
           $("label > input").change(function() {
               if ($(this).is(":checked")) {
                   $(this).parent().css("background", checkedBackground);
                   $(\'input[type="radio"]:not(:checked)\').parent().css("background",originalBackground);
                   console.log("Value: " + $(\'input[type="radio"]:checked\').val()); 
               } 
           });
</script>' .  <<<EOD
    <script src="{$assetsUrl}js/progressButton.js"></script>
    <script>
        
        var bttn = document.getElementById('ugm_submit_button');
        var old = '';
        new ProgressButton( bttn, {
                callback : function( instance ) {
                    
                    var progress = 0,
                        interval = setInterval( function() {
                            // console.log('Progress: ' + progress);
                            var button_text = document.getElementById('button_content').textContent ||
                                document.getElementById('button_content').innerText;
                            if (button_text == '[[+ugm_downloading_files]]' && button_text != old) {
                                progress = 0.1;
                                old = button_text;
                            } else if (button_text == '[[+ugm_unzipping_files]]' && button_text != old) {
                                progress = 0.3;
                                old = button_text;
                            } else if (button_text == '[[+ugm_copying_files]]' && button_text != old) {
                                progress = 0.6;
                                old = button_text;
                            } else if (button_text == '[[+ugm_preparing_setup]]' && button_text != old) {
                                progress = 0.8;
                                old = button_text;
                            }  else if (button_text == '[[+ugm_finished]]') {
                                progress = 1;
                            }  else if (button_text == '[[+ugm_launching_setup]]') {
                                progress = 1;
                            }
                            // progress = Math.min( progress + Math.random() * 0.1, 1 );
                            progress = Math.min( progress, 1 );
                            // console.log("Text " + button_text);
                            if( progress === 1 ) {
                                setTimeout(function () {
                                    instance._stop(1);
                                    clearInterval( interval );
                                }, 1000);
                            }
                            instance._setProgress( progress );
                            if( progress === 1 ) {
                                setTimeout(function () {
                                    instance._stop(1);
                                    clearInterval( interval );
                                }, 1000);
                            }
                        }, 1000 );
                }
            } );
        
        /* Simulate click on landing page to initiate action; button won't submit
           because it's not in a form 
         */
        setTimeout(function () {
           // bttn.click();
        }, 1000);
    </script>
EOD;


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

if (php_sapi_name() === 'cli') {
    echo $tpl;
}

return $tpl;