<?php
/**
 * de:properties.inc.php topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
 *
 * German Translation by Sebastian G. Marinescu
 * Created on 08-18-2016
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
 * de:properties.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/



/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Github token - verfügbar von ihrem GitHub Profil';
$_lang['ugm_github_username_desc'] = 'Ihr Benutzername auf GitHub';

$_lang['ugm_version_list_path_desc'] = 'Pfad zur Versionslistendatei (ohne Dateiname -- sollte mit einem Schrägstrich enden); Standard: {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Anzahl der Versuche um Daten von Github oder MODX zu empfangen; Standard: 2';
$_lang['ugm_github_timeout_desc'] = 'Zeitüberschreitung in Sekunden für die Überprüfung von Github; Standard: 6';
$_lang['ugm_modx_timeout_desc'] = 'Zeitüberschreitung in Sekunden für die Überprüfung des Downloadstatus von MODX; Standard: 6';
$_lang['ugm_groups_desc'] = 'Gruppe, oder komma-separierte Liste von Gruppen, welche das Widget sehen dürfen';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Verstecken des Widgets, wenn kein Update verfügbar ist; Standard: Nein';
$_lang['ugm_interval_desc'] = 'Intervall zwischen Überprüfungen -- Beispiel: 1 week, 3 days, 6 hours; Standard: 1 week';
$_lang['ugm_lastCheck_desc'] = 'Datum und Zeit der letzten Überprüfung -- wird automatisch gesetzt';
$_lang['ugm_latestVersion_desc'] = 'Neueste Version (bei der letzten Überprüfung)-- wird automatisch gesetzt';
$_lang['ugm_plOnly_desc'] = 'Anzeigen nur von "pl" (stabile) Versionen; Standard: Ja';
$_lang['ugm_versionsToShow_desc'] = 'Anzahl der anzuzeigenden Version im Update-Formular (nicht Widget); Standard: 5';
$_lang['ugm_language_desc'] = 'Zwei-Zeichen Sprachcode (ISO 639-1) für die zu verwendete Sprache; Standard: en';
$_lang['ugm_forcePclZip_desc'] = 'Erzwingen der Nutzung von PclZip anstatt von ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Erzwingen der Nutzung von fopen anstatt von cURL für das Herunterladen';
$_lang['ugm_ssl_verify_peer_desc'] = 'Zur Sicherheit, cURL die Identität des Servers verifizieren lassen';
