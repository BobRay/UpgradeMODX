<?php
/**
 * Resolver for UpgradeMODX extra
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
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

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
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
                        'content' => '[[!UpgradeMODXWidget]]',
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