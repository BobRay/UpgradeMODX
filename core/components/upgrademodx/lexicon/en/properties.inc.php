<?php
/**
 * en:properties.inc.php topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
 * Created on 08-21-2015
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
 * en:properties.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/



/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Github token - available from your GitHub profile';
$_lang['ugm_github_username_desc'] = 'Your username at GitHub';
$_lang['ugm_version_list_path_desc'] = 'Path to versionlist file (minus the filename -- should end in a slash); Default: {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Number of tries to get data from GitHub or MODX; default: 2';
$_lang['ugm_github_timeout_desc'] = 'Timeout in seconds for checking Github; default: 6';
$_lang['ugm_modx_timeout_desc'] = 'Timeout in seconds for checking download status from MODX; default: 6';
$_lang['ugm_groups_desc'] = 'group, or commma-separated list of groups, who will see the widget';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Hide widget when no upgrade is available: default: No';
$_lang['ugm_interval_desc'] = 'Interval between checks -- Examples: 1 week, 3 days, 6 hours; default: 1 week';
$_lang['ugm_lastCheck_desc'] = 'Date and time of last check -- set automatically';
$_lang['ugm_latestVersion_desc'] = 'Latest version (at last check) -- set automatically';
$_lang['ugm_plOnly_desc'] = 'Show only pl (stable) versions; default: yes';
$_lang['ugm_versionsToShow_desc'] = 'Number of versions to show in upgrade form (not widget); default: 5';
$_lang['ugm_language_desc'] = 'Two-letter language code for language to use; default: en';
$_lang['ugm_forcePclZip_desc'] = 'Force the use of PclZip instead of ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Force the use of fopen instead of cURL for the download';
$_lang['ugm_ssl_verify_peer_desc'] = 'For security, have cURL verify the identity of the server';