<?php
/**
 * sv default topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2016-2018 by Kristoffer Karlström <http://www.kmmtiming.se>
 * 2018 Update by mrhaw
 * Created on 09-08-2015
 * Edited on 11-30-2018
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

/* Used in unzipfiles.class.php */
$_lang['ugm_files_to_extract'] = 'objekt att packa upp';
$_lang['ugm_destination'] = 'Destination';
$_lang['ugm_source'] = 'Käll';
$_lang['ugm_unzipped'] = 'Uppackade';
$_lang['ugm_no_downloaded_file'] = 'Kunde inte hitta den nedladdade filen';
$_lang['ugm_could_not_create_directory'] = 'Kunde inte skapa katalog';
$_lang['ugm_directory_not_writable'] = 'Katalogen är inte skrivbar';


/* Used in transport.settings.php */
$_lang['setting_ugm_file_version'] = 'Filversion';
$_lang['setting_ugm_file_version_desc'] = 'Version då filen med versionslistan uppdaterades. Sätts automatiskt -- ändra inte!';
$_lang['setting_ugm_temp_dir'] = 'Tempkatalog';
$_lang['setting_ugm_temp_dir_desc'] = 'Sökväg till katalogen som används temporärt för att lagra och packa upp nedladdade filer; Måste vara skrivbar; default:{base_path}ugmtemp/';
$_lang['setting_ugm_versionlist_api_url'] = 'API URL för versionslista';
$_lang['setting_ugm_versionlist_api_url_desc'] = 'URL för API att hämta versionslista från; default: //api.github.com/repos/modxcms/revolution/tags';
$_lang['setting_ugm_version_list_path'] = 'Sökväg till versionslista';
$_lang['setting_ugm_version_list_path_desc'] = 'Sökvägen till filen med versionslistan (minus filnamnet -- ska avslutas med snedstreck); Default: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_last_check'] = 'Senaste kontroll';
$_lang['setting_ugm_last_check_desc'] = 'Datum och tid för senaste kontrollen -- sätts automatiskt';
$_lang['setting_ugm_latest_version'] = 'Senaste version';
$_lang['setting_ugm_latest_version_desc'] = 'Senaste version (vid senaste kontrollen) -- sätts automatiskt';
$_lang['setting_ugm_hide_when_no_upgrade'] = 'Dölj när ingen uppdatering finns';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Dölj widgeten när det inte finns några tillgängliga uppdateringar: default: Nej';
$_lang['setting_ugm_interval'] = 'Intervall';
$_lang['setting_ugm_interval_desc'] = 'Intervall mellan kontroller -- Exempelvis: 1 vecka, 3 dagar, 6 timmar; default: 1 dag';
$_lang['setting_ugm_groups'] = 'grupper';
$_lang['setting_ugm_groups_desc'] = 'grupp, eller kommaseparerad lista av grupper, som kommer att se widgeten';
$_lang['setting_ugm_versions_to_show'] = 'Versioner att visa';
$_lang['setting_ugm_versions_to_show_desc'] = 'Antal versioner som ska visas i uppgraderingsformuläret; default: 5';
$_lang['setting_ugm_github_timeout'] = 'GitHub timeout';
$_lang['setting_ugm_github_timeout_desc'] = 'Timeout i sekunder vid kontroll mot Github; default: 6';
$_lang['setting_ugm_github_token'] = 'GitHub token';
$_lang['setting_ugm_github_token_desc'] = 'Github token - tillängligt från din profil på GitHub';
$_lang['setting_ugm_github_username'] = 'Användarnamn på GitHub';
$_lang['setting_ugm_github_username_desc'] = 'Ditt användarnamn på GitHub';
$_lang['setting_ugm_pl_only'] = 'Bara pl versioner';
$_lang['setting_ugm_pl_only_desc'] = 'Visa enbart pl (stabila) versioner; default: Ja';
$_lang['setting_ugm_language'] = 'Språk';
$_lang['setting_ugm_language_desc'] = 'Tvåbokstavskod för språk som ska användas; default: en';
$_lang['setting_ugm_ssl_verify_peer'] = 'Verifiera SSL identitet';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'Av säkerhetsskäl, låt cURL verifiera serverns identitet';
$_lang['setting_ugm_modx_timeout'] = 'MODX Timeout';
$_lang['setting_ugm_modx_timeout_desc'] = 'Timeout i sekunder för att kontrollera status mot MODX; default: 6';
$_lang['setting_ugm_force_pcl_zip'] = 'Force PclZip';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Tvinga användande av PclZip istället för ZipArchive';

$_lang['setting_ugm_cert_path'] = 'Sökväg till certifikat';
$_lang['setting_ugm_cert_path_desc'] = 'Sökväg till SSL certifikatfil i .pem format; sällan nödvändigt';

/* System Setting Area strings */
$_lang['Download'] = 'Ladda ner';
$_lang['Form'] = 'Formulär';
$_lang['GitHub'] = 'GitHub';
$_lang['Security'] = 'Säkerhet';
$_lang['Widget'] = 'Widget';


/* Used in copyfiles.class.php */
$_lang['ugm_copied'] = 'Kopierad';
$_lang['ugm_to'] = 'till';
$_lang['ugm_files_copied'] = 'Objekt kopierade';

/* Used in downloadfiles.class.php */
$_lang['ugm_downloaded'] = 'Nerladdad';
$_lang['ugm_download_failed'] = 'Nerladdning misslyckades';

/* Used in preparesetup.class.php */
$_lang['ugm_no_root_config_core'] = 'Kunde inte hitta config.core.php';
$_lang['ugm_setup_prepared'] = 'Setup förberedd';
$_lang['ugm_could_not_write'] = 'Kunde inte skriva';

/* Used in cleanup.class.php */
$_lang['ugm_deleting_temp_files'] = 'Städar upp';
$_lang['ugm_temp_files_deleted'] = 'Städning slutförd (temporära filer borttagna)';