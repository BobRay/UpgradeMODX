<?php
/**
 * it:properties.inc.php topic lexicon file for UpgradeMODX extra
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
 * it:properties.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package upgrademodx
 **/



/* Used in properties.upgrademodxwidget.snippet.php */
$_lang['ugm_github_token_desc'] = 'Github token - disponibile nel tuo profilo GitHub';
$_lang['ugm_github_username_desc'] = 'Il tuo username di GitHub';
$_lang['ugm_version_list_path_desc'] = 'Percorso a versionlist file (senza il nome del file -- deve terminare con la barra); Default: {core_path}cache/upgrademodx/';
$_lang['ubm_attempts_desc'] = 'Numero di tentativi per prendere le informazioni da GitHub o da MODX; default: 2';
$_lang['ugm_github_timeout_desc'] = 'Timeout in secondi per il controllo in Github; default: 6';
$_lang['ugm_modx_timeout_desc'] = 'Timeout in secondi per il controllo del download da MODX; default: 6';
$_lang['ugm_groups_desc'] = 'gruppo, o lista separata da virgola di gruppi, che possono vedere il widget';
$_lang['ugm_hideWhenNoUpgrade_desc'] = 'Nascondi il widget quando non ci sono aggiornamenti disponibili: default: No';
$_lang['ugm_interval_desc'] = 'Intervallo tra i controlli -- Esempio: 1 week, 3 days, 6 hours; default: 1 week';
$_lang['ugm_lastCheck_desc'] = 'Data ed ora dell\'ultimo controllo -- imposta automatico';
$_lang['ugm_latestVersion_desc'] = 'Ultima versione (dell\'ultimo controllo) -- imposta automatico';
$_lang['ugm_plOnly_desc'] = 'Mostra solo le versioni pl (stable); default: yes';
$_lang['ugm_versionsToShow_desc'] = 'Numero di versioni da mostrare nel form degli aggiornamenti (non il widget); default: 5';
$_lang['ugm_language_desc'] = 'Codice della lingua (due lettere) da usare; default: en';
$_lang['ugm_forcePclZip_desc'] = 'Forza l\'uso di PclZip invece di ZipArchive';
$_lang['ugm_forceFopen_desc'] = 'Forza l\'uso di fopen invede di cURL per il download';
$_lang['ugm_ssl_verify_peer_desc'] = 'Per sicurezza, fare verificare a cURL l\'identita del server';