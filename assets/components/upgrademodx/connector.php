<?php
/**
* Connector file for UpgradeMODX extra
*
* Copyright 2015-2018 by Bob Ray <https://bobsguides.com>
* Created on 07-16-2018
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
/* @var $modx modX */

@include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
if (! defined('MODX_CORE_PATH')) {
   /* For development environment */
   require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/config.core.php';
}
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$upgrademodxCorePath = $modx->getOption('ugm.core_path', null, $modx->getOption('core_path') . 'components/upgrademodx/');

require_once $upgrademodxCorePath . 'model/upgrademodx/upgrademodx.class.php';
$modx->upgrademodx = new UpgradeMODX($modx);
$modx->lexicon->load('upgrademodx:default');
$path = $upgrademodxCorePath . 'processors/';

/* handle request */
$_SERVER['HTTP_MODAUTH'] = $modx->user->getUserToken('mgr');

$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));