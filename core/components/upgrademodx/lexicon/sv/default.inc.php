<?php
/**
 * sv default topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2016 by Kristoffer Karlström <http://www.kmmtiming.se>
 * 2018 Update by mrhaw
 * Created on 09-08-2015
 * Edited on 04-24-2016
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
 * sv default topic lexicon strings
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
$_lang['ugm_current_version_caption'] = 'Aktuell version';
$_lang['ugm_latest_version_caption'] = 'Senaste version';
$_lang['ugm_no_version_list'] = 'Kunde inte hämta versionslistan';
$_lang['ugm_could_not_open'] = 'Kunde inte öppna';
$_lang['ugm_for_writing'] = 'för redigering';
$_lang['ugm_upgrade_available'] = 'Uppgradering tillgänglig';
$_lang['ugm_modx_up_to_date'] = 'MODX är uppdaterat';
$_lang['ugm_error'] = 'Fel';
$_lang['ugm_logout_note'] = 'OBS: Alla användare kommer att loggas ut';
$_lang['ugm_upgrade_modx'] = 'Uppgradera MODX';
$_lang['ugm_json_decode_failed'] = 'Misslyckades med att avkoda JSON för versionsinformation från GitHub i upgradeAvailable()';
$_lang['ugm_no_curl_no_fopen'] = 'Varken allow_url_fopen eller cURL kan användas för att söka efter uppgraderingar';

$_lang['ugm_no_version_list_from_github'] = 'Misslyckades att hämta versionslista från GitHub';
$_lang['ugm_no_such_version'] = 'Efterfrågad version existerar inte';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'misslyckades';

$_lang['ugm_missing_versionlist'] = "Saknar fil 'versionlist'; Pröva att ladda om sidan";
$_lang['ugm_cannot_read_directory'] = 'Kan inte läsa filmappsinnehåll eller så är filmappen tom';
$_lang['ugm_unknown_error_reading_temp'] = 'Okänt fel (error) under läsning av filmapp: /temp';
$_lang['no_method_enabled'] = 'Kan inte ladda ner filerna - Varken cURL eller allow_url_fopen är tillgängliga på denna server.';
$_lang['ugm_cannot_read_config_core_php'] = 'Kan inte läsa config.core.php';
$_lang['ugm_cannot_read_main_config'] = 'Kan inte läsa huvud-konfigurationsfil';
$_lang['ugm_could_not_find_cacert'] = 'Kan inte hitta cacert.pem';
$_lang['ugm_could_not_write_progress'] = 'Kan inte skriva till fil: ugmprogress';
$_lang['ugm_file'] = 'Fil';
$_lang['ugm_is_empty_download_failed'] = 'är tom -- Nerladdning misslyckades';
$_lang['ugm_unable_to_create_directory'] = 'Kan inte skapa filmapp';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Kan inte läsa från filmapp: /ugmtemp';
$_lang['ugm_file_copy_failed'] = 'Misslyckades att kopiera fil';
$_lang['ugm_begin_upgrade'] = 'Starta Upgradering';
$_lang['ugm_starting_upgrade'] = 'Upgradering Startar';
$_lang['ugm_downloading_files'] = 'Laddar ner filer';
$_lang['ugm_unzipping_files'] = '"Unzipping" Filer';
$_lang['ugm_copying_files'] = 'Kopierar Filer';
$_lang['ugm_preparing_setup'] = 'Förbereder Inställningssida';
$_lang['ugm_launching_setup'] = 'Startar Inställningssida';
$_lang['ugm_finished'] = 'Klart';
$_lang['ugm_get_major_versions'] = '<b>Viktigt:</b> Det är <i>absolut</i> rekomenderat att du installerar alla MODX versioner som slutar med .0 mellan din nuvarande version och aktuell version.';
$_lang['ugm_current_version_indicator'] = 'aktuell version';
$_lang['ugm_using'] = 'Använder';
$_lang['ugm_choose_version'] = 'Välj MODX Version För Uppgradering';
$_lang['ugm_updating_modx_files'] = 'Uppdaterar MODX Filer';
$_lang['ugm_originally_created_by'] = 'Ursprungligen skapad av';
$_lang['ugm_modified_for_revolution_by'] = 'Modifierad för "Revolution only" av';
$_lang['ugm_modified_for_upgrade_by'] = 'Modifierad för "Upgrade only with dashboard widget" av';
$_lang['ugm_original_design_by'] = 'Original Design Av';
$_lang['ugm_back_to_manager'] = 'Tillbaka till MODX Manager';

/* Used in System Settings */
$_lang['setting_ugm_github_token_desc'] = 'Github token - tillängligt från din profil på GitHub';
$_lang['setting_ugm_github_username_desc'] = 'Ditt användarnamn på GitHub';
$_lang['setting_ugm_version_list_path_desc'] = 'Sökväg till filen med versionshistorik (minus filnamnet -- avslutad med ett slash); Default: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_github_timeout_desc'] = 'Timeout i sekunder för kontroll mot Github; default: 6';
$_lang['setting_ugm_modx_timeout_desc'] = 'Timeout i sekunder för att kontrollera status mot MODX; default: 6';
$_lang['setting_ugm_groups_desc'] = 'grupp, eller kommaseparerad lista av grupper, som kommer att se widgeten';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Dölj widgeten när det inte finns några tillgängliga uppdateringar: default: Nej';
$_lang['setting_ugm_interval_desc'] = 'Intervall mellan kontroller -- Exempelvis: 1 vecka, 3 dagar, 6 timmar; default: 1 dag';
$_lang['setting_ugm_last_check_desc'] = 'Datum och tid för senaste kontrollen -- sätts automatiskt';
$_lang['setting_ugm_latest_version_desc'] = 'Senaste version (vid senaste kontrollen) -- sätts automatiskt';
$_lang['setting_ugm_pl_only_desc'] = 'Visa enbart pl (stabila) versioner; default: Ja';
$_lang['setting_ugm_versions_to_show_desc'] = 'Antal versioner som ska visas i uppgraderingsformuläret; default: 5';
$_lang['setting_ugm_language_desc'] = 'Tvåbokstavskod för språk som ska användas; default: en';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Tvinga användande av PclZip istället för ZipArchive';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'Av säkerhetsskäl, låt cURL verifiera serverns identitet';

$_lang['setting_ugm_version_list_path'] = 'Versionlist path';
$_lang['setting_ugm_github_timeout'] = 'GitHub Timeout';
$_lang['setting_ugm_modx_timeout'] = 'MODX Timeout';
$_lang['setting_ugm_versionlist_api_url'] = 'Version List API URL';
$_lang['setting_ugm_versionlist_api_url_desc'] = 'URL of API to get version list from; default: //api.github.com/repos/modxcms/revolution/tags';
$_lang['setting_ugm_temp_dir'] = 'Temp directory';
$_lang['setting_ugm_temp_dir_desc'] = 'Path to the directory used for temporary storage for downloading and unzipping files; Must be writable; default:{base_path}ugmtemp/';
$_lang['ugm_download_failed'] = 'Download failed';
$_lang['setting_ugm_cert_path'] = 'Cert Path';
$_lang['setting_ugm_cert_path_desc'] = 'Path to SSL cert file in .pem format; rarely necessary';
$_lang['setting_ugm_file_version'] = 'File Version';
$_lang['setting_ugm_file_version_desc'] = 'Version when versionlist file was last updated. Set automatically -- do not edit!';

/* System Setting Area strings */
$_lang['Download'] = 'Download';
$_lang['Form'] = 'Form';
$_lang['GitHub'] = 'GitHub';
$_lang['Security'] = 'Security';
$_lang['Widget'] = 'Widget';

/* Used in copyfiles.class.php */
$_lang['ugm_copied'] = 'Copied';
$_lang['ugm_to'] = 'to';
$_lang['ugm_files_copied'] = 'Objects copied';

/* Used in downloadfiles.class.php */
$_lang['ugm_downloaded'] = 'Downloaded';
$_lang['ugm_download_failed'] = 'Download failed';

/* Used in preparesetup.class.php */
$_lang['ugm_no_root_config_core'] = 'Could not find root config.core.php';
$_lang['ugm_setup_prepared'] = 'Setup prepared';
$_lang['ugm_could_not_write'] = 'Could not write';

/* Used in cleanup.class.php */
$_lang['ugm_deleting_temp_files'] = 'Cleaning Up';
$_lang['ugm_temp_files_deleted'] = 'Cleanup Completed (temporary files removed)';