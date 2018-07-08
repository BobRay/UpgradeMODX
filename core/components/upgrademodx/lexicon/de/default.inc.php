<?php
/**
 * de default topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2015-2018 Bob Ray <https://bobsguides.com>
 *
 * German Translation by Sebastian G. Marinescu
 * Created on 08-18-2016
 * Updated on 07-08-2018
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
 * de default topic lexicon strings
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
$_lang['ugm_current_version_caption'] = 'Aktuelle Version';
$_lang['ugm_latest_version_caption'] = 'Neueste Version';
$_lang['ugm_no_version_list'] = 'Konnte keine Versions-Liste empfangen';
$_lang['ugm_could_not_open'] = 'Konnte nicht geöffnet werden';
$_lang['ugm_for_writing'] = 'zum Schreiben';
$_lang['ugm_upgrade_available'] = 'Update verfügbar';
$_lang['ugm_modx_up_to_date'] = 'MODX ist aktuell';
$_lang['ugm_error'] = 'Fehler';
$_lang['ugm_logout_note'] = 'Hinweis: Alle Nutzer werden abgemeldet';
$_lang['ugm_upgrade_modx'] = 'Aktualisieren Sie MODX';
$_lang['ugm_json_decode_failed'] = 'JSON-Dekodierung für Versionsdaten von Github in upgradeAvailable() fehlgeschlagen';
$_lang['ugm_no_curl_no_fopen'] = 'Weder allow_url_fopen noch cURL können zur Überprüfung von Updates verwendet werden';

$_lang['ugm_no_version_list_from_github'] = 'Konnte Versionsliste von GitHub nicht erhalten';
$_lang['ugm_no_such_version'] = 'Angeforderte Version existiert nicht';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'fehlgeschlagen';

$_lang['ugm_missing_versionlist'] = 'Fehlende "Versionslistendatei"; versuchen Sie das Neuladen der Dashboard-Seite';
$_lang['ugm_cannot_read_directory'] = 'Lesen des Verzeichnisinhaltes nicht möglich oder Verzeichnis leer';
$_lang['ugm_unknown_error_reading_temp'] = 'Unbekannter Fehler beim Lesen des /temp Verzeichnisses';
$_lang['no_method_enabled'] = 'Konnte Dateien nicht herunterladen - weder cURL noch allow_url_fopen sind auf diesem Server aktiviert.';
$_lang['ugm_cannot_read_config_core_php'] = 'Konnte "config.core.php" nicht lesen';
$_lang['ugm_cannot_read_main_config'] = 'Konnte Haupt-Konfigurationsdatei nicht lesen';
$_lang['ugm_could_not_find_cacert'] = 'Konnte "cacert.pem" nicht finden';
$_lang['ugm_could_not_write_progress'] = 'Konnte Datei "ugmprogress" nicht schreiben';
$_lang['ugm_file'] = 'Datei';
$_lang['ugm_is_empty_download_failed'] = 'ist leer -- Download fehlgeschlagen';
$_lang['ugm_unable_to_create_directory'] = 'Erstellung des Verzeichnisses fehlgeschlagen';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Lesen des /ugmtemp Verzeichnisses nicht möglich';
$_lang['ugm_file_copy_failed'] = 'Kopieren der Datei fehlgeschlagen';
$_lang['ugm_begin_upgrade'] = 'Beginne Aktualisierung';
$_lang['ugm_starting_upgrade'] = 'Starte Aktualisierung';
$_lang['ugm_downloading_files'] = 'Dateien werden heruntergeladen';
$_lang['ugm_unzipping_files'] = 'Dateien werden entpackt';
$_lang['ugm_copying_files'] = 'Dateien werden kopiert';
$_lang['ugm_preparing_setup'] = 'Setup wird vorbereitet';
$_lang['ugm_launching_setup'] = 'Setup wird ausgeführt';
$_lang['ugm_finished'] = 'Fertig';
$_lang['ugm_get_major_versions'] = '<b>Wichtig:</b> Es wird <i>strengstens</i> empfohlen, dass Sie alle Versionen, die auf eine .0 enden und zwischen ihrer derzeitigen und der aktuellsten Version von MODX liegen, installieren.';
$_lang['ugm_current_version_indicator'] = 'Aktuelle Version';
$_lang['ugm_using'] = 'Benutze';
$_lang['ugm_choose_version'] = 'Wählen Sie die MODX Version für die Aktualisierung';
$_lang['ugm_updating_modx_files'] = 'Aktualisiere MODX Dateien';
$_lang['ugm_originally_created_by'] = 'Ursprünglich erstellt von';
$_lang['ugm_modified_for_revolution_by'] = 'Modifiziert für Revolution einzig von';
$_lang['ugm_modified_for_upgrade_by'] = 'Modifiziert für UpgradeModx mit Dashboard-Widget einzig von';
$_lang['ugm_original_design_by'] = 'Ursprüngliches Design von';
$_lang['ugm_back_to_manager'] = 'Zurück zum Manager';
