<?php
/**
 * en default topic lexicon file for UpgradeMODX extra
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
 *
 * @package upgrademodx
 */

/**
 * Description
 * -----------
 * en default topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/

$_lang['package'] = 'UpgradeModx';

/* Used in upgrademodxwidget.snippet.php */
$_lang['ugm_current_version_caption'] = 'Current Version';
$_lang['ugm_latest_version_caption'] = 'Latest Version';
$_lang['ugm_empty_return'] = 'Empty return';
$_lang['ugm_no_version_list'] = 'Could not get version list';
$_lang['ugm_no_version_list_from_github'] = 'Could not get version list from GitHub';
$_lang['ugm_could_not_open'] = 'Could not open';
$_lang['ugm_for_writing'] = 'for writing';
$_lang['ugm_missing_properties'] = 'lastCheck or interval properties not set';
$_lang['ugm_upgrade_available'] = 'Upgrade Available';
$_lang['ugm_modx_up_to_date'] = 'MODX is up to date';
$_lang['ugm_error'] = 'Error';
$_lang['ugm_logout_note'] = 'Note: All users will be logged out';
$_lang['ugm_upgrade_modx'] = 'Upgrade MODX';
$_lang['ugm_json_decode_failed'] = 'Failed JSON decode for version data from GitHub in upgradeAvailable()';
$_lang['ugm_not_available'] = 'New version not yet available for download at MODX repo';
$_lang['ugm_no_curl_no_fopen'] = 'Neither allow_url_fopen nor cURL can be used to check for upgrades';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'failed';