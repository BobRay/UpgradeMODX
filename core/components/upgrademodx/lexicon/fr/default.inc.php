<?php
/**
 * fr topic lexicon file for UpgradeMODX extra
 * Translation by Philippe delberghe (AmaZili) <philippe@amazili.com>
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
 *
 * @package upgrademodx
 */

/**
 * Description
 * -----------
 * fr topic lexicon strings
 * Translation by : Philippe Delberghe (AmaZili)
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
$_lang['ugm_current_version_caption'] = 'Version Courante';
$_lang['ugm_latest_version_caption'] = 'Dernière Version';
$_lang['ugm_no_version_list'] = 'Impossible d\'ouvrir la liste des versions';
$_lang['ugm_could_not_open'] = 'Impossible d\'ouvrir';
$_lang['ugm_for_writing'] = 'en écriture';
$_lang['ugm_upgrade_available'] = 'Mise à jour disponible';
$_lang['ugm_modx_up_to_date'] = 'MODX est à jour';
$_lang['ugm_error'] = 'Erreur';
$_lang['ugm_logout_note'] = 'Note: tous les utilisateurs doivent être déconnectés';
$_lang['ugm_upgrade_modx'] = 'Mise à jour de MODX';
$_lang['ugm_json_decode_failed'] = 'JSON decode échoué pour les données de version de GitHub dans upgradeAvailable()';
$_lang['ugm_no_curl_no_fopen'] = 'Seuls allow_url_fopen ou cURL peuvent être utilisés pour vérifier les mises à jour';

$_lang['ugm_no_version_list_from_github'] = 'Impossible d\'obtenir la liste des versions depuis GitHub';
$_lang['ugm_no_such_version'] = 'Version demandée inexistante';



/* Used in upgrademodx.class.php */

$_lang['failed'] = 'failed';

$_lang['ugm_missing_versionlist'] = "Ficher 'versionlist' absent; Essayez de recharger la page de tableau de bord";
$_lang['ugm_cannot_read_directory'] = 'Lec ture du répertoire impossible ou répertoire inexistant';
$_lang['ugm_unknown_error_reading_temp'] = 'Erreur inconnue lors de la lecture du répertoire /temp';
$_lang['no_method_enabled'] = 'Impossible de télécharger les fichers - les fonctions cURL et allow_url_fopen sont désactivées sur ce serveur.';
$_lang['ugm_cannot_read_config_core_php'] = 'Impossible de lire config.core.php';
$_lang['ugm_cannot_read_main_config'] = 'Impossible de lire le fichier de configuration principal';
$_lang['ugm_could_not_find_cacert'] = 'le fichier cacert.pem est introuvable';
$_lang['ugm_could_not_write_progress'] = 'Ecriture dans le fichier ugmprogress impossible';
$_lang['ugm_file'] = 'Le fichier';
$_lang['ugm_is_empty_download_failed'] = 'est vide -- téléchargement échoué';
$_lang['ugm_unable_to_create_directory'] = 'impossbible de créer le répertoire';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Lecture du répertoire /ugmtemp impossible';
$_lang['ugm_file_copy_failed'] = 'Copie de ficher échouée';
$_lang['ugm_begin_upgrade'] = 'Démarrage de la mise à jour';
$_lang['ugm_starting_upgrade'] = 'Démarrage de la mise à jour';
$_lang['ugm_downloading_files'] = 'Téléchargment des fichiers';
$_lang['ugm_unzipping_files'] = 'Décompression des fichiers';
$_lang['ugm_copying_files'] = 'Copie des fichiers';
$_lang['ugm_preparing_setup'] = 'Préparation installation';
$_lang['ugm_launching_setup'] = 'Lancement installation';
$_lang['ugm_finished'] = 'Terminé';
$_lang['ugm_get_major_versions'] = '<b>Important :</b> Il est <i>fortement</i> recommandé d\'installer toutes les version se terminant par .0 entre votre version et la version courante de MODX.';
$_lang['ugm_current_version_indicator'] = 'version courante';
$_lang['ugm_using'] = 'Utilise';
$_lang['ugm_choose_version'] = 'Choisir la version de MODx pour la mise à jour';
$_lang['ugm_updating_modx_files'] = 'Mise à jour des fichiers de MODX';
$_lang['ugm_originally_created_by'] = 'Créé par';
$_lang['ugm_modified_for_revolution_by'] = 'Modifié pour Revolution seulement par';
$_lang['ugm_modified_for_upgrade_by'] = 'Modifié pour mise à jour seulement avec le gadget de tableau de bord par';
$_lang['ugm_original_design_by'] = 'Design original de';
$_lang['ugm_back_to_manager'] = 'Retour au Manager';

/* Used in System Settings */
$_lang['setting_ugm_github_token_desc'] = 'Jeton Github - disponible depuis votre profil GitHub';
$_lang['setting_ugm_github_username_desc'] = 'Votre nom d\'utilisateur GitHub';
$_lang['setting_ugm_version_list_path_desc'] = 'Chemin vers le fichier versionlist (signe moins sur le fichier -- doit se terminer avec un slash); Défaut: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_github_timeout_desc'] = 'Délai en secondes pour vérifer Github; défaut: 6';
$_lang['setting_ugm_modx_timeout_desc'] = 'Délai en secondes pour vérifer le statut de téléchargement de MODX; défaut: 6';
$_lang['setting_ugm_groups_desc'] = 'groupe, ou liste séparée par des virgules de groupes, qui verront le gadget';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Chacher le gadget quand aucune mise à jour n\'est disponble: défaut: Non';
$_lang['setting_ugm_interval_desc'] = 'Intervale entre les vérifications  -- Exmples: 1 semaine, 3 jours, 6 heures; défaut: 1 jour';
$_lang['setting_ugm_last_check_desc'] = 'Date et heure de la dernière vérification -- définie automatiquement';
$_lang['setting_ugm_latest_version_desc'] = 'Version la plus récente (à la dernière vérification) -- définie automatiquement';
$_lang['setting_ugm_pl_only_desc'] = 'Montrer seulement les versions stables (pl); défaut: oui';
$_lang['setting_ugm_versions_to_show_desc'] = 'Nombre de versions à montrer dans le formulaire de mise à jour; défaut: 5';
$_lang['setting_ugm_language_desc'] = 'Code de la langue a utiliser en deux lettres; défaut: en';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Forcer l\'usage de PclZip à la place de ZipArchive';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'Par sécurité, demander à cURL de vérifier l\'identité du serveur';

$_lang['setting_ugm_file_version'] = 'File Version';
$_lang['setting_ugm_file_version_desc'] = 'Version when versionlist file was last updated. Set automatically -- do not edit!';
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