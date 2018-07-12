<?php
/**
 * en:properties.inc.php topic lexicon file for UpgradeMODX extra
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
 * en:properties.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/



/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Token Github - disponbile depuis votre profil GitHub';
$_lang['ugm_github_username_desc'] = 'Votre nom d\'utilisateur sur GitHub';
$_lang['ugm_version_list_path_desc'] = 'Chemin vers le fichier versionlist (sans le nom du fichier -- doit se terminer avec un slash); Défaut : {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Nombre d\'essais pour obtenir les données depuis GitHub ou MODX; défaut : 2';
$_lang['ugm_github_timeout_desc'] = 'Timeout en secondes pour tester l\'accès à Github; défaut : 6';
$_lang['ugm_modx_timeout_desc'] = 'Timeout en secondes pour tester le statut de téléchargement depuis MODX; default: 6';
$_lang['ugm_groups_desc'] = 'un groupe, ou une liste de groupes séparés par des virgules, qui pourront voir le widget';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Cacher le widget lorsqu\'aucune mise à jour n\'est disponible : défaut : Non';
$_lang['ugm_interval_desc'] = 'Délai entre deux vérifications -- Exemples : 1 semaine, 3 jours, 6 heures; défaut: 1 semaine';
$_lang['ugm_lastCheck_desc'] = 'Date et heure de la dernière vérification -- configuré automatiquement';
$_lang['ugm_latestVersion_desc'] = 'Version la plus récente (depuis la dernière vérification) -- configuré automatiquement';
$_lang['ugm_plOnly_desc'] = 'Afficher uniquement les versions pl (stables); défaut : oui';
$_lang['ugm_versionsToShow_desc'] = 'Nombre de versions à afficher dans le formulaire de mise à jour (pas le widget); défaut : 5';
$_lang['ugm_language_desc'] = 'Bigramme du code pays pour la langue à utiliser; défaut : en';
$_lang['ugm_forcePclZip_desc'] = 'Forcer l\'utilisation de PclZip au lieu de ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Forcer l\'utilisation de fopen au lieu de cURL pour le téléchargement';
$_lang['ugm_ssl_verify_peer_desc'] = 'Par sécurité, configurer cURL pour qu\'il vérifie l\'identité du serveur';
