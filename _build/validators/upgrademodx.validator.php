<?php
/**
 * Validator for UpgradeMODX extra
 *
 * Copyright 2015-2018 by Bob Ray <https://bobsguides.com>
 * Created on 10-06-2018
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
            /* return false if conditions are not met */

            /* [[+code]] */
            break;
        case xPDOTransport::ACTION_UPGRADE:
            /* return false if conditions are not met */
            $widgetSnippet = $modx->getObject('modSnippet', array('name' => 'UpgradeMODXWidget'));
            if ($widgetSnippet) {
                $props = $widgetSnippet->get('properties');
                /* Do nothing if snippet has no properties */
                if (!empty($props)) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Saving snippet properties');
                    foreach ($props as $propName => $settingKey) {
                        $value = $props[$propName]['value'];
                        if ($props[$propName]['type'] == 'combo-boolean') {
                            $props[$propName]['value'] = empty($value) ? '0' : '1';
                        }
                    }
                    $settingsToSave = $modx->toJSON($props);
                    $_SESSION['ugm_saved_settings'] = $settingsToSave;
                }
            }

            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}

return true;