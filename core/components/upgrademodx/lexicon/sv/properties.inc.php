<?php
/**
 * sv:properties.inc.php topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2016 by Kristoffer Karlström <http://www.kmmtiming.se>
 * Created on 09-08-2015
 * Updated on 04-24-2016
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
 * sv:properties.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/
 
 
 
/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Github token - tillängligt från din profil på GitHub';
$_lang['ugm_github_username_desc'] = 'Ditt användarnamn på GitHub';
$_lang['ugm_version_list_path_desc'] = 'Sökväg till filen med versionshistorik (minus filnamnet -- avslutad med ett slash); Default: {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Antal försök som ska göras för att komma åt data från GitHub eller MODX; default: 2';
$_lang['ugm_github_timeout_desc'] = 'Timeout i sekunder för kontroll mot Github; default: 6';
$_lang['ugm_modx_timeout_desc'] = 'Timeout i sekunder för att kontrollera status mot MODX; default: 6';
$_lang['ugm_groups_desc'] = 'grupp, eller kommaseparerad lista av grupper, som kommer att se widgeten';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Dölj widgeten när det inte finns några tillgängliga uppdateringar: default: Nej';
$_lang['ugm_interval_desc'] = 'Intervall mellan kontroller -- Exempelvis: 1 vecka, 3 dagar, 6 timmar; default: 1 vecka';
$_lang['ugm_lastCheck_desc'] = 'Datum och tid för senaste kontrollen -- sätts automatiskt';
$_lang['ugm_latestVersion_desc'] = 'Senaste version (vid senaste kontrollen) -- sätts automatiskt';
$_lang['ugm_plOnly_desc'] = 'Visa enbart pl (stabila) versioner; default: Ja';
$_lang['ugm_versionsToShow_desc'] = 'Antal versioner som ska visas i uppgraderingsformuläret (inte i widgeten); default: 5';
$_lang['ugm_language_desc'] = 'Tvåbokstavskod för språk som ska användas; default: en';
$_lang['ugm_forcePclZip_desc'] = 'Tvinga användande av PclZip istället för ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Tvinga användande av fopen istället cURL för nedladdningen';
$_lang['ugm_ssl_verify_peer_desc'] = 'Av säkerhetsskäl, låt cURL verifiera serverns identitet';
