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

            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $doc = $modx->getObject('modResource', array('alias' => 'upgrade-modx'));
            if ($doc) {
                $doc->remove();
            }

            $widget = $modx->getObject('modDashboardWidget', array('name' => 'Upgrade MODX', 'type' => 'snippet'));
            if ($widget) {
                $widget->remove();
            }

            break;
    }
}

return true;