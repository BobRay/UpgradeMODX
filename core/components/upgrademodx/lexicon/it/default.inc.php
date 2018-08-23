<?php
/**
 * it default topic lexicon file for UpgradeMODX extra
 *
 * Copyright 2016 by FerX https://github.com/FerX
 * Created on 14/08/2016
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
 * it default topic lexicon strings
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
$_lang['ugm_current_version_caption'] = 'Versione Corrente';
$_lang['ugm_latest_version_caption'] = 'Ultima Versione';
$_lang['ugm_no_version_list'] = 'Impossibile recuperare l\'elenco delle versioni';
$_lang['ugm_could_not_open'] = 'Impossibile aprire';
$_lang['ugm_for_writing'] = 'per scrivere';
$_lang['ugm_upgrade_available'] = 'Aggiornamento Disponibile';
$_lang['ugm_modx_up_to_date'] = 'MODX &egrave; aggiornato';
$_lang['ugm_error'] = 'Errore';
$_lang['ugm_logout_note'] = 'Nota: Tutti gli utenti devono essere disconnessi';
$_lang['ugm_upgrade_modx'] = 'Aggiorna MODX';
$_lang['ugm_json_decode_failed'] = 'Decodifica JSON non riuscita per i dati della versione da GitHub in upgradeAvailable()';
$_lang['ugm_no_curl_no_fopen'] = 'I comandi allow_url_fopen e cURL non possono essere usati per controllare gli aggiornamenti';

$_lang['ugm_no_version_list_from_github'] = 'Could not get version list from GitHub';
$_lang['ugm_no_such_version'] = 'Requested version does not exist';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'fallito';

$_lang['ugm_missing_versionlist'] = "Missing 'versionlist' file; try reloading the dashboard page";
$_lang['ugm_cannot_read_directory'] = 'Unable to read directory contents or directory is empty';
$_lang['ugm_unknown_error_reading_temp'] = 'Unknown error reading /temp directory';
$_lang['no_method_enabled'] = 'Cannot download the files - neither cURL nor allow_url_fopen is enabled on this server.';
$_lang['ugm_cannot_read_config_core_php'] = 'Could not read config.core.php';
$_lang['ugm_cannot_read_main_config'] = 'Cannot Read main config file';
$_lang['ugm_could_not_find_cacert'] = 'Could not find cacert.pem';
$_lang['ugm_could_not_write_progress'] = 'Could not write to ugmprogress file';
$_lang['ugm_file'] = 'File';
$_lang['ugm_is_empty_download_failed'] = 'is empty -- download failed';
$_lang['ugm_unable_to_create_directory'] = 'Unable to create directory';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Unable to read from /ugmtemp directory';
$_lang['ugm_file_copy_failed'] = 'File Copy Failed';
$_lang['ugm_begin_upgrade'] = 'Begin Upgrade';
$_lang['ugm_starting_upgrade'] = 'Starting Upgrade';
$_lang['ugm_downloading_files'] = 'Downloading Files';
$_lang['ugm_unzipping_files'] = 'Unzipping Files';
$_lang['ugm_copying_files'] = 'Copying Files';
$_lang['ugm_preparing_setup'] = 'Preparing Setup';
$_lang['ugm_launching_setup'] = 'Launching Setup';
$_lang['ugm_finished'] = 'Finished';
$_lang['ugm_get_major_versions'] = '<b>Important:</b> It is <i>strongly</i> recommended that you install all of the versions ending in .0 between your version and the current version of MODX.';
$_lang['ugm_current_version_indicator'] = 'current version';
$_lang['ugm_using'] = 'Using';
$_lang['ugm_choose_version'] = 'Choose MODX Version for Upgrade';
$_lang['ugm_updating_modx_files'] = 'Updating MODX Files';
$_lang['ugm_originally_created_by'] = 'Originally created by';
$_lang['ugm_modified_for_revolution_by'] = 'Modified for Revolution only by';
$_lang['ugm_modified_for_upgrade_by'] = 'Modified for Upgrade only with dashboard widget by';
$_lang['ugm_original_design_by'] = 'Original Design By';
$_lang['ugm_back_to_manager'] = 'Back to Manager';

/* Used in System Settings */
$_lang['setting_ugm_github_token_desc'] = 'Github token - disponibile nel tuo profilo GitHub';
$_lang['setting_ugm_github_username_desc'] = 'Il tuo username di GitHub';
$_lang['setting_ugm_version_list_path_desc'] = 'Percorso a versionlist file (senza il nome del file -- deve terminare con la barra); Default: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_github_timeout_desc'] = 'Timeout in secondi per il controllo in Github; default: 6';
$_lang['setting_ugm_modx_timeout_desc'] = 'Timeout in secondi per il controllo del download da MODX; default: 6';
$_lang['setting_ugm_groups_desc'] = 'gruppo, o lista separata da virgola di gruppi, che possono vedere il widget';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Nascondi il widget quando non ci sono aggiornamenti disponibili: default: No';
$_lang['setting_ugm_interval_desc'] = 'Intervallo tra i controlli -- Esempio: 1 week, 3 days, 6 hours; default: 1 day';
$_lang['setting_ugm_last_check_desc'] = 'Data ed ora dell\'ultimo controllo -- imposta automatico';
$_lang['setting_ugm_latest_version_desc'] = 'Ultima versione (dell\'ultimo controllo) -- imposta automatico';
$_lang['setting_ugm_pl_only_desc'] = 'Mostra solo le versioni pl (stable); default: yes';
$_lang['setting_ugm_versions_to_show_desc'] = 'Numero di versioni da mostrare nel form degli aggiornamenti; default: 5';
$_lang['setting_ugm_language_desc'] = 'Codice della lingua (due lettere) da usare; default: en';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Forza l\'uso di PclZip invece di ZipArchive';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'Per sicurezza, fare verificare a cURL l\'identita del server';

$_lang['setting_ugm_version_list_path'] = 'Versionlist path';
$_lang['setting_ugm_github_timeout'] = 'GitHub Timeout';
$_lang['setting_ugm_modx_timeout'] = 'MODX Timeout';
$_lang['setting_ugm_versionlist_api_url'] = 'Version List API URL';
$_lang['setting_ugm_versionlist_api_url_desc'] = 'URL of API to get version list from; default: //api.github.com/repos/modxcms/revolution/tags';
$_lang['setting_ugm_temp_dir'] = 'Temp directory';
$_lang['setting_ugm_temp_dir_desc'] = 'Path to the directory used for temporary storage for downloading and unzipping files; Must be writable; default:{base_path}ugmtemp/';
$_lang['ugm_download_failed'] = 'Download failed';