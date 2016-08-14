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
$_lang['ugm_empty_return'] = 'Risultato vuoto';
$_lang['ugm_no_version_list'] = 'Impossibile recuperare l\'elenco delle versioni';
$_lang['ugm_could_not_open'] = 'Impossibile aprire';
$_lang['ugm_for_writing'] = 'per scrivere';
$_lang['ugm_missing_properties'] = 'lastCheck o intervallo non impostato';
$_lang['ugm_upgrade_available'] = 'Aggiornamento Disponibile';
$_lang['ugm_modx_up_to_date'] = 'MODX &egrave; aggiornato';
$_lang['ugm_error'] = 'Errore';
$_lang['ugm_logout_note'] = 'Nota: Tutti gli utenti devono essere disconnessi';
$_lang['ugm_upgrade_modx'] = 'Aggiorna MODX';
$_lang['ugm_json_decode_failed'] = 'Decodifica JSON non riuscita per i dati della versione da GitHub in upgradeAvailable()';
$_lang['ugm_not_available'] = 'La nuova versione non &egrave; ancora disponibile per il download sul MODX repo';
$_lang['ugm_no_curl_no_fopen'] = 'I comandi allow_url_fopen e cURL non possono essere usati per controllare gli aggiornamenti';

/* Used in upgrademodx.class.php */
$_lang['failed'] = 'fallito';