<?php
/**
 * de default topic lexicon file for UpgradeMODX extra
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
$_lang['ugm_json_decode_failed'] = 'JSON decode für Versionsdaten von Github in upgradeAvailable() fehlgeschlagen';
$_lang['ugm_no_curl_no_fopen'] = 'Weder allow_url_fopen noch cURL können zur Überprüfung von Updates verwendet werden';

$_lang['ugm_no_version_list_from_github'] = 'Konnte Versionsliste von GitHub nicht laden';
$_lang['ugm_no_such_version'] = 'Angeforderte Version existiert nicht';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'fehlgeschlagen';

$_lang['ugm_missing_versionlist'] = "Fehlende 'versionlist' Datei; laden Sie die Dashboard-Seite neu";
$_lang['ugm_cannot_read_directory'] = 'Ordner-Inhalte können nicht gelesen werden oder Ordner ist leer';
$_lang['ugm_unknown_error_reading_temp'] = 'Unbekannter Fehler beim Lesen des /temp Verzeichnisses';
$_lang['no_method_enabled'] = 'Dateien können nicht heruntergeladen werden - weder cURL noch allow_url_fopen sind auf dem Server aktiv.';
$_lang['ugm_cannot_read_config_core_php'] = 'Konnte config.core.php nicht lesen';
$_lang['ugm_cannot_read_main_config'] = 'Konnte Haupt-Konfigurationsdatei nicht lesen';
$_lang['ugm_could_not_find_cacert'] = 'Konnte cacert.pem nicht finden';
$_lang['ugm_could_not_write_progress'] = 'Konnte nicht in ugmprogress Datei schreiben';
$_lang['ugm_file'] = 'Datei';
$_lang['ugm_is_empty_download_failed'] = 'ist leer -- Download fehlgeschlagen';
$_lang['ugm_unable_to_create_directory'] = 'Erstellen des Verzeichnisses fehlgeschlagen';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Konnte nicht aus /ugmtemp Verzeichnis lesen';
$_lang['ugm_file_copy_failed'] = 'Dateikopie fehlgeschlagen';
$_lang['ugm_begin_upgrade'] = 'Beginne Upgrade';
$_lang['ugm_starting_upgrade'] = 'Starte Upgrade';
$_lang['ugm_downloading_files'] = 'Dateien werden heruntergeladen';
$_lang['ugm_unzipping_files'] = 'Dateien werden entpackt';
$_lang['ugm_copying_files'] = 'Dateien werden kopiert';
$_lang['ugm_preparing_setup'] = 'Setup wird vorbereitet';
$_lang['ugm_launching_setup'] = 'Setup wird gestartet';
$_lang['ugm_finished'] = 'Erledigt';
$_lang['ugm_get_major_versions'] = '<b>Wichtig:</b> Es wird <i>stärkstens</i> empfohlen, dass Sie alle Versionen, die auf .0 enden, zwischen ihrer Version und der aktuellen Version von MODX installieren.</p>';
$_lang['ugm_current_version_indicator'] = 'aktuelle Version';
$_lang['ugm_using'] = 'Verwendung von';
$_lang['ugm_choose_version'] = 'Wählen Sie die MODX Version für das Upgrade';
$_lang['ugm_updating_modx_files'] = 'Aktualisiere MODX Dateien';
$_lang['ugm_originally_created_by'] = 'Ursprünglich erstellt von';
$_lang['ugm_modified_for_revolution_by'] = 'Modifiziert für Revolution einzigst von';
$_lang['ugm_modified_for_upgrade_by'] = 'Modifiziert zum reinen Upgrade-Skript mit Dashboard-Widget von';
$_lang['ugm_original_design_by'] = 'Original Design von';
$_lang['ugm_back_to_manager'] = 'Zurück zum Manager';

/* Used in System Settings */
$_lang['setting_ugm_github_token_desc'] = 'Github token - verfügbar von ihrem GitHub Profil';
$_lang['setting_ugm_github_username_desc'] = 'Ihr Benutzername auf GitHub';
$_lang['setting_ugm_version_list_path_desc'] = 'Pfad zur Versionslistendatei (ohne Dateiname -- sollte mit einem Schrägstrich enden); Standard: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_github_timeout_desc'] = 'Zeitüberschreitung in Sekunden für die Überprüfung von Github; Standard: 6';
$_lang['setting_ugm_modx_timeout_desc'] = 'Zeitüberschreitung in Sekunden für die Überprüfung des Downloadstatus von MODX; Standard: 6';
$_lang['setting_ugm_groups_desc'] = 'Gruppe, oder komma-separierte Liste von Gruppen, welche das Widget sehen dürfen';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Verstecken des Widgets, wenn kein Update verfügbar ist; Standard: Nein';
$_lang['setting_ugm_interval_desc'] = 'Intervall zwischen Überprüfungen -- Beispiel: 1 week, 3 days, 6 hours; Standard: 1 day';
$_lang['setting_ugm_last_check_desc'] = 'Datum und Zeit der letzten Überprüfung -- wird automatisch gesetzt';
$_lang['setting_ugm_latest_version_desc'] = 'Neueste Version (bei der letzten Überprüfung)-- wird automatisch gesetzt';
$_lang['setting_ugm_pl_only_desc'] = 'Anzeigen nur von "pl" (stabile) Versionen; Standard: Ja';
$_lang['setting_ugm_versions_to_show_desc'] = 'Anzahl der anzuzeigenden Version im Update-Formular; Standard: 5';
$_lang['setting_ugm_language_desc'] = 'Zwei-Zeichen Sprachcode (ISO 639-1) für die zu verwendete Sprache; Standard: en';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Erzwingen der Nutzung von PclZip anstatt von ZipArchive';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'Zur Sicherheit, cURL die Identität des Servers verifizieren lassen';

$_lang['setting_ugm_file_version'] = 'Datei Version';
$_lang['setting_ugm_file_version_desc'] = 'Version, als die Versionslistendatei zuletzt aktualisiert wurde. Wird automatisch gesetzt -- nicht editieren!';
$_lang['setting_ugm_force_pcl_zip'] = 'Erzwinge PclZip';
$_lang['setting_ugm_version_list_path'] = 'Versionlist Pfad';
$_lang['setting_ugm_github_timeout'] = 'GitHub Zeitüberschreitung';
$_lang['setting_ugm_modx_timeout'] = 'MODX Zeitüberschreitung';
$_lang['setting_ugm_versionlist_api_url'] = 'Versionslisten API-URL';
$_lang['setting_ugm_versionlist_api_url_desc'] = 'URL der API für Versionsliste; Standard: //api.github.com/repos/modxcms/revolution/tags';
$_lang['setting_ugm_temp_dir'] = 'Temp Verzeichnis';
$_lang['setting_ugm_temp_dir_desc'] = 'Pfad zu dem Verzeichnis, das für den temporären Speicher zum Herunterladen und Entpacken von Dateien verwendet wird; Muss beschreibbar sein; Standard: {base_path}ugmtemp/';
$_lang['ugm_download_failed'] = 'Download fehlgeschlagen';
$_lang['setting_ugm_cert_path'] = 'Cert Pfad';
$_lang['setting_ugm_cert_path_desc'] = 'Pfad zu SSL-Zertifikat Datei im .pem Format (selten benötigt)';

/* System Setting Area strings */
$_lang['Download'] = 'Download';
$_lang['Form'] = 'Formular';
$_lang['GitHub'] = 'GitHub';
$_lang['Security'] = 'Sicherheit';
$_lang['Widget'] = 'Widget';

/* Used in copyfiles.class.php */
$_lang['ugm_copied'] = 'Kopiert';
$_lang['ugm_to'] = 'nach';
$_lang['ugm_files_copied'] = 'Dateien kopiert';

/* Used in downloadfiles.class.php */
$_lang['ugm_downloaded'] = 'Heruntergeladen';
$_lang['ugm_download_failed'] = 'Download fehlgeschlagen';

/* Used in preparesetup.class.php */
$_lang['ugm_no_root_config_core'] = 'Konnte config.core.php im Root nicht finden';
$_lang['ugm_setup_prepared'] = 'Installation vorbereitet';
$_lang['ugm_could_not_write'] = 'Konnte nicht schreiben';

/* Used in cleanup.class.php */
$_lang['ugm_deleting_temp_files'] = 'Am Aufräumen';
$_lang['ugm_temp_files_deleted'] = 'Aufräumen komplett (temporäre Dateien gelöscht)';