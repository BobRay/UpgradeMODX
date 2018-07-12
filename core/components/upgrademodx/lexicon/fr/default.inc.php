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
$_lang['ugm_current_version_caption'] = 'Version Courante';
$_lang['ugm_latest_version_caption'] = 'Dernière Version';
$_lang['ugm_no_version_list'] = 'Accès impossible à la liste des versions';
$_lang['ugm_could_not_open'] = 'N\'a pu être ouvert';
$_lang['ugm_for_writing'] = 'en écriture';
$_lang['ugm_upgrade_available'] = 'Mise à jour disponible';
$_lang['ugm_modx_up_to_date'] = 'MODX est à jour';
$_lang['ugm_error'] = 'Erreur';
$_lang['ugm_logout_note'] = 'Note: Tous les utilisateurs seront délogués';
$_lang['ugm_upgrade_modx'] = 'Mise à jour MODX';
$_lang['ugm_json_decode_failed'] = 'Décodage JSON des données de version fournies par GitHub en erreur dans upgradeAvailable()';
$_lang['ugm_no_curl_no_fopen'] = 'Impossible de vérifier la disponibilité de mises à jour en utilisant allow_url_fopen ou cURL';

$_lang['ugm_no_version_list_from_github'] = 'Impossible d\'obtenir la liste des versions depuis GitHub';
$_lang['ugm_no_such_version'] = 'La version demandée n\'existe pas';



/* Used in upgrademodx.class.php */

$_lang['failed'] = 'échoué';

$_lang['ugm_missing_versionlist'] = "Le fichier 'versionlist' est introuvable; essayez de recharger la page du tableau de bord";
$_lang['ugm_cannot_read_directory'] = 'Impossible de lire le contenu du répertoire ou le répertoire est vide';
$_lang['ugm_unknown_error_reading_temp'] = 'Erreur inconnue lors de la lecture du répertoire /temp';
$_lang['no_method_enabled'] = 'Les fichiers ne peuvent être téléchargés - ni cURL ni allow_url_fopen ne sont activés sur ce serveur.';
$_lang['ugm_cannot_read_config_core_php'] = 'Impossible de lire le fichier config.core.php';
$_lang['ugm_cannot_read_main_config'] = 'Impossible de lire le fichier de configuration principal';
$_lang['ugm_could_not_find_cacert'] = 'Le fichier cacert.pem est introuvable';
$_lang['ugm_could_not_write_progress'] = 'Impossible d\'écrire dans le fichier ugmprogress';
$_lang['ugm_file'] = 'Fichier';
$_lang['ugm_is_empty_download_failed'] = 'est vide -- téléchargement en erreur';
$_lang['ugm_unable_to_create_directory'] = 'Impossible de créer le répertoire';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Impossible de lire le répertoire /ugmtemp';
$_lang['ugm_file_copy_failed'] = 'La copie du fichier a échoué';
$_lang['ugm_begin_upgrade'] = 'Début de la mise à jour';
$_lang['ugm_starting_upgrade'] = 'Démarrage de la mise à jour';
$_lang['ugm_downloading_files'] = 'Téléchargement des fichiers';
$_lang['ugm_unzipping_files'] = 'Décompression des fichiers';
$_lang['ugm_copying_files'] = 'Copie des fichiers';
$_lang['ugm_preparing_setup'] = 'Préparation du Setup';
$_lang['ugm_launching_setup'] = 'Démarrage du Setup';
$_lang['ugm_finished'] = 'Terminé';
$_lang['ugm_get_major_versions'] = '<b>Important:</b> Il est <i>fortement</i> conseillé que vous installiez toutes les versions se terminant par .0 entre votre version et la version courante de MODX.</p>';
$_lang['ugm_current_version_indicator'] = 'version courante';
$_lang['ugm_using'] = 'Utilisant';
$_lang['ugm_choose_version'] = 'Choisissez la version de MODX pour la mise à jour';
$_lang['ugm_updating_modx_files'] = 'Mise à jour des fichiers MODX';
$_lang['ugm_originally_created_by'] = 'Créé initialement par';
$_lang['ugm_modified_for_revolution_by'] = 'Modifié pour Revolution par seulement';
$_lang['ugm_modified_for_upgrade_by'] = 'Modifié pour une mise à jour seule depuis le widget du tableau de bord par';
$_lang['ugm_original_design_by'] = 'Conçu initialement par';
$_lang['ugm_back_to_manager'] = 'Retour au Manager';
