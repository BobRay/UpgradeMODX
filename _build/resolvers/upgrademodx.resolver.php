<?php
/**
 * Resolver for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
 * Created on 08-13-2015
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
 * @package upgrademodx
 * @subpackage build
 */

/* @var $object xPDOObject */
/* @var $modx modX */

/* @var array $options */

$resourceContent = '<script>
var ugmConnectorUrl = "[[++assets_url]]components/upgrademodx/connector.php";
var ugm_config = {"ugm_setup_url":"[[++site_url]]setup\/index.php"};
var ugm_setup_url = "[[++site_url]]setup/index.php";
</script>
<link rel="stylesheet" href="[[++assets_url]]components/upgrademodx/css/progress.css" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js?v=263pl"></script>
<script type="text/javascript" src="[[++assets_url]]components/upgrademodx/js/modernizr.custom.js?v=263pl"></script>
[[!UpgradeMODXWidget]]';

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /* Remove VersionList file */
            $path = MODX_CORE_PATH . 'cache/upgrademodx/versionlist';
            if (file_exists($path)) {
                unlink($path);
            }
            /* Create resource if we're pre-widget */
            if (!file_exists(MODX_CORE_PATH . 'model\modx\moddashboardwidget.class.php')) {
                $doc = $modx->getObject('modResource', array('alias' => 'upgrade-modx'));
                if (! $doc) {
                    /** @var $doc modResource */
                    $doc = $modx->newObject('modResource');
                    $doc->fromArray(array(
                        'pagetitle' => 'UpgradeMODX',
                        'alias' => 'upgrade-modx',
                        'description' => 'View this resource to check for upgrades if your MODX version shows no widget',
                        'content' => $resourceContent,
                        'published' => false,
                        'hidemenu' => true,
                    ), '', false, true );
                    $doc->save();

                }

            } else {
                /* Attempt to remove resource */
                $doc = $modx->getObject('modResource', array('alias' => 'upgrade-modx'));
                if ($doc) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Removing UpgradeMODX Resource');
                    $doc->remove();
                }
            }

            /* Update Setting Values from Snippet Properties  if snippet has properties*/

        /* Move snippet-property values to System-Setting-key values
         * Key is property key, value is setting key.
         * Do nothing if there's no Widget */


        $settings = array(
            'versionListPath' => 'ugm_version_list_path', //ok
            'hideWhenNoUpgrade' => 'ugm_hide_when_no_upgrade', //ok
            // 'interval' => 'ugm_interval', //ok  CHANGED TO '1 day' in install
            'groups' => 'ugm_groups', //ok
            'versionsToShow' => 'ugm_versions_to_show', //ok
            'githubTimeout' => 'ugm_github_timeout', //ok
            'github_token' => 'ugm_github_token', //ok
            'github_username' => 'ugm_github_username', //ok
            'plOnly' => 'ugm_pl_only', //ok
            'language' => 'ugm_language', //ok
            'ssl_verify_peer' => 'ugm_ssl_verify_peer', //ok
            'modxTimeout' => 'ugm_modx_timeout', //ok
            'forcePclZip' => 'ugm_force_pcl_zip', //ok
            // 'ugm.attempts' => 'ugm_attempts', // removed
            // 'ugm.forceFopen' => 'ugm_forceFopen', // removed

            /* New Settings -- install will create */
            /*'ugm_temp_dir' => '{base_path}ugmtemp/',
            'ugm_versionlist_api_url' => '//api.github.com/repos/modxcms/revolution/tags',
            'ugm_cert_path' => '',*/
        );
        $savedSettings = $modx->getOption('ugm_saved_settings', $_SESSION, null, true);
        if ( !empty($savedSettings))  {
            $modx->log(modX::LOG_LEVEL_INFO, 'Updating Setting Values from Snippet Properties');
            $props = $modx->fromJSON($savedSettings);
            $output = '';
            if (!empty ($props)) {
                foreach ($settings as $propName => $settingKey) {
                    // $output .= "\n<br> Setting System {$settingKey} to {$value}";
                    $setting = $modx->getObject('modSystemSetting', $settingKey);
                    if ($setting) {
                        $value = $props[$propName]['value'];
                        $setting->set('value', $value);
                        $setting->save();
                    } else {
                        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find setting with key: ' . $settingKey);
                    }
                }
            }
        }

        /* Empty latest version and last check settings */
        $check = $modx->getObject('modSystemSetting', array('key' => 'ugm_last_check'));
        if ($check) {
            $check->set('value', '');
            $check->save();
        }
        $latest = $modx->getObject('modSystemSetting', array('key' => 'ugm_latest_version'));
        if ($latest) {
            $latest->set('value', '');
            $latest->save();
        }

        unset($check, $latest, $savedSettings, $settings);

        $chunk = $modx->getObject('modChunk', array('name' => 'UpgradeMODXSnippetScriptSource'));
        if ($chunk) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Removing ScriptSource chunk');
            $chunk->remove();
        }

        /* Delete old properties lex files */
        $path = MODX_CORE_PATH . 'components/upgrademodx/lexicon/';

        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $file = $path . $fileinfo->getFilename() . '/properties.inc.php';
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        }

        /* Delete old files */

        $files = array(
            MODX_CORE_PATH . 'components/upgrademodx/elements/chunks/upgrademodxsnippetscriptsource.chunk.php',
            MODX_CORE_PATH . 'components/upgrademodx/elements/chunks/upgrademodxsnippetscriptsource.chunk.zip',
            MODX_CORE_PATH . 'components/upgrademodx/elements/chunks/modx.zip',
        );

        foreach($files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        $dir = MODX_CORE_PATH . 'components/upgrademodx/elements/chunks/ugmtemp';
        if (is_dir($dir)) {
            @rmdir($dir);
        }



        /* Refresh System Setting Cache */
        $modxVersion = $modx->getOption('settings_version', null);
        $cm = $modx->getCacheManager();
        if (version_compare($modxVersion, '2.1.0-pl') >= 0) {
            $cacheRefreshOptions = array('system_settings' => array());
            $cm->refresh($cacheRefreshOptions);
        }

        break;

        case xPDOTransport::ACTION_UNINSTALL:
            $doc = $modx->getObject('modResource', array('alias' => 'upgrade-modx'));
            if ($doc) {
                $doc->remove();
            }
            break;
    }
}

return true;