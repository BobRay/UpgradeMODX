<?php
/**
 * fr:properties.inc.php topic lexicon file for UpgradeMODX extra
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
 * fr:properties.inc.php topic lexicon strings
 * Translation by : Philippe Delberghe (AmaZili)
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/


/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Jeton Github - disponible depuis votre profil GitHub';
$_lang['ugm_github_username_desc'] = 'Votre nom d\'utilisateur GitHub';
$_lang['ugm_version_list_path_desc'] = 'Chemin vers le fichier versionlist (signe moins sur le fichier -- doit se terminer avec un slash); Défaut: {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Nombre d\'essais pour atteindre les données depuis Github ou MODX; défaut: 2';
$_lang['ugm_github_timeout_desc'] = 'Délai en secondes pour vérifer Github; défaut: 6';
$_lang['ugm_modx_timeout_desc'] = 'Délai en secondes pour vérifer le statut de téléchargement de MODX; défaut: 6';
$_lang['ugm_groups_desc'] = 'groupe, ou liste séparée par des virgules de groupes, qui verront le gadget';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Chacher le gadget quand aucune mise à jour n\'est disponble: défaut: Non';
$_lang['ugm_interval_desc'] = 'Intervale entre les vérifications  -- Exmples: 1 semaine, 3 jours, 6 heures; défaut: 1 semaine';
$_lang['ugm_lastCheck_desc'] = 'Date et heure de la dernière vérification -- définie automatiquement';
$_lang['ugm_latestVersion_desc'] = 'Version la plus récente (à la dernière vérification) -- définie automatiquement';
$_lang['ugm_plOnly_desc'] = 'Montrer seulement les versions stables (pl); défaut: oui';
$_lang['ugm_versionsToShow_desc'] = 'Nombre de versions à montrer dans le formulaire de mise à jour (pas dans le gadget); défaut: 5';
$_lang['ugm_language_desc'] = 'Code de la langue a utiliser en deux lettres; défaut: en';
$_lang['ugm_forcePclZip_desc'] = 'Forcer l\'usage de PclZip à la place de ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Forcer l\'usage de fopen à la place de cURL pour le téléchargment';
$_lang['ugm_ssl_verify_peer_desc'] = 'Par sécurité, demander à cURL de vérifier l\'identité du serveur';