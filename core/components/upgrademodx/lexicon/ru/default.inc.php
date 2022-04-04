<?php
/**
 * ru default topic lexicon file for UpgradeMODX extra
 * Russian translation by Anton Tarasov (Himurovich) - 12-15-2018
 * Copyright 2015-2022 Bob Ray <https://bobsguides.com>
 * Created on 11-23-2018
 *
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
 * ru default topic lexicon strings
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
$_lang['ugm_current_version_caption'] = 'Текущая версия';
$_lang['ugm_latest_version_caption'] = 'Последняя версия';
$_lang['ugm_no_version_list'] = 'Невозможно получить список версий';
$_lang['ugm_could_not_open'] = 'Невозможно открыть';
$_lang['ugm_for_writing'] = 'для записи';
$_lang['ugm_upgrade_available'] = 'Обновление доступно';
$_lang['ugm_modx_up_to_date'] = 'MODX актуален';
$_lang['ugm_error'] = 'Ошибка';
$_lang['ugm_logout_note'] = 'Внимание: все сеансы пользователей будут завершены';
$_lang['ugm_upgrade_modx'] = 'Обновить MODX';
$_lang['ugm_json_decode_failed'] = 'Не удалось декодировать JSON для данных версии из GitHub в upgradeAvailable()';
$_lang['ugm_no_curl_no_fopen'] = 'Ни allow_url_fopen, ни cURL не могут использоваться для проверки обновлений';

$_lang['ugm_no_version_list_from_github'] = 'Невозможно получить список версий из GitHub';
$_lang['ugm_no_such_version'] = 'Запрошенная версия не существует';


/* Used in upgrademodx.class.php */

$_lang['failed'] = 'прервано';

$_lang['ugm_missing_versionlist'] = "Отсутствует файл версий; пожалуйста, попробуйте перезагрузить главную страницу панели управления";
$_lang['ugm_cannot_read_directory'] = 'Невозможно прочитать содержимое директории или она пуста';
$_lang['ugm_unknown_error_reading_temp'] = 'Неизвестная ошибка при чтении /temp директории';
$_lang['no_method_enabled'] = 'Невозможно загрузить файлы - ни cURL, ни allow_url_fopen не включены на этом сервере.';
$_lang['ugm_cannot_read_config_core_php'] = 'Невозможно прочитать config.core.php';
$_lang['ugm_cannot_read_main_config'] = 'Невозможно прочитать главный файл конфигурации';
$_lang['ugm_could_not_find_cacert'] = 'Невозможно найти cacert.pem';
$_lang['ugm_could_not_write_progress'] = 'Невозможно записать в файл ugmprogress';
$_lang['ugm_file'] = 'Файл';
$_lang['ugm_is_empty_download_failed'] = 'пусто -- закачивание прервано';
$_lang['ugm_unable_to_create_directory'] = 'Невозможно создать директорию';
$_lang['ugm_unable_to_read_ugmtemp'] = 'Невозможно записать из /ugmtemp';
$_lang['ugm_file_copy_failed'] = 'Копирование файла прервано';
$_lang['ugm_begin_upgrade'] = 'Начать обновление';
$_lang['ugm_starting_upgrade'] = 'Обновление запускается';
$_lang['ugm_downloading_files'] = 'Файлы скачиваются';
$_lang['ugm_unzipping_files'] = 'Файлы распаковываются';
$_lang['ugm_copying_files'] = 'Файлы копируются';
$_lang['ugm_preparing_setup'] = 'Подготовка к установке';
$_lang['ugm_launching_setup'] = 'Запуск установки';
$_lang['ugm_finished'] = 'Завершено';
$_lang['ugm_get_major_versions'] = '<b>Важное замечание:</b> Настоятельно рекомендуется установить все версии, заканчивающиеся на .0, между установленной у вас и текущей версией MODX.';
$_lang['ugm_current_version_indicator'] = 'текущая версия';
$_lang['ugm_using'] = 'Используется';
$_lang['ugm_choose_version'] = 'Выберите версию MODX для обновления';
$_lang['ugm_updating_modx_files'] = 'Обновление файлов MODX';
$_lang['ugm_originally_created_by'] = 'Изначально создано';
$_lang['ugm_modified_for_revolution_by'] = 'Модифицировано для Revolution';
$_lang['ugm_modified_for_upgrade_by'] = 'Изменено только для обновления с виджетом панели управления';
$_lang['ugm_original_design_by'] = 'Оригинальный дизайн от';
$_lang['ugm_back_to_manager'] = 'Назад в Менеджер';

/* Used in unzipfiles.class.php */
$_lang['ugm_files_to_extract'] = 'объекты к извлечению';
$_lang['ugm_destination'] = 'Назначение';
$_lang['ugm_source'] = 'Источник';
$_lang['ugm_unzipped'] = 'Распокованы';
$_lang['ugm_no_downloaded_file'] = 'Невозможно найти скаченный файл';
$_lang['ugm_could_not_create_directory'] = 'Невозможно создать директорию';
$_lang['ugm_directory_not_writable'] = 'Директория не доступна для записи';


/* Used in transport.settings.php */
$_lang['setting_ugm_file_version'] = 'Версия файла';
$_lang['setting_ugm_file_version_desc'] = 'Версия, когда файл списка версий последний раз обновлялся. Устанавливается автоматически - не редактировать!';
$_lang['setting_ugm_temp_dir'] = 'Временная директория для UpgradeMODX';
$_lang['setting_ugm_temp_dir_desc'] = 'Путь к каталогу, используемому для временного хранения в процессе скачивания и распаковки файлов; Должно быть доступно для записи; по умолчанию: {base_path}ugmtemp/';
$_lang['setting_ugm_versionlist_api_url'] = 'API URL для списка версий';
$_lang['setting_ugm_versionlist_api_url_desc'] = 'API URL для получения списка версий';
$_lang['setting_ugm_version_list_path'] = 'Файл со списком версий';
$_lang['setting_ugm_version_list_path_desc'] = 'Путь к файлу списка версий (без имени файла - должен заканчиваться косой чертой); По умолчанию: {core_path}cache/upgrademodx/';
$_lang['setting_ugm_last_check'] = 'Последняя проверка';
$_lang['setting_ugm_last_check_desc'] = 'Дата и время последней проверки - устанавливается автоматически';
$_lang['setting_ugm_latest_version'] = 'Последняя версия';
$_lang['setting_ugm_latest_version_desc'] = 'Последняя версия (при последней проверке) - устанавливается автоматически';
$_lang['setting_ugm_hide_when_no_upgrade'] = 'Скрыть виджет без обновлений';
$_lang['setting_ugm_hide_when_no_upgrade_desc'] = 'Скрыть виджет, когда обновление недоступно: по умолчанию: Нет';
$_lang['setting_ugm_interval'] = 'Интервал';
$_lang['setting_ugm_interval_desc'] = 'Интервал между проверками -- Например: 1 week, 3 days, 6 hours; по умолчанию: 1 day';
$_lang['setting_ugm_groups'] = 'Группы';
$_lang['setting_ugm_groups_desc'] = 'Группа, или разделенный запятыми список групп, которым будет доступен виджет';
$_lang['setting_ugm_versions_to_show'] = 'Количество версий';
$_lang['setting_ugm_versions_to_show_desc'] = 'Количество версий для отображения в форме обновления; по умолчанию: 5';
$_lang['setting_ugm_github_timeout'] = 'GitHub таймаут';
$_lang['setting_ugm_github_timeout_desc'] = 'Тайм-аут в секундах для проверки Github; по умолчанию: 6';
$_lang['setting_ugm_github_token'] = 'GitHub Токен';
$_lang['setting_ugm_github_token_desc'] = 'Github токен - доступен в вашем GitHub профиле';
$_lang['setting_ugm_github_username'] = 'Имя пользователя GitHub';
$_lang['setting_ugm_github_username_desc'] = 'Ваше имя пользователя GitHub';
$_lang['setting_ugm_pl_only'] = 'Только pl версии';
$_lang['setting_ugm_pl_only_desc'] = 'Показывать только стабильные(pl) версии; по умолчанию: да';
$_lang['setting_ugm_language'] = 'Язык';
$_lang['setting_ugm_language_desc'] = 'Двухбуквенный код языка; по умолчанию: en';
$_lang['setting_ugm_ssl_verify_peer'] = 'Проверка SSL источника';
$_lang['setting_ugm_ssl_verify_peer_desc'] = 'В целях безопасности cURL проверяет подлинность сервера';
$_lang['setting_ugm_modx_timeout'] = 'MODX таймаут';
$_lang['setting_ugm_modx_timeout_desc'] = 'Тайм-аут в секундах для проверки статуса загрузки из MODX; по умолчанию: 6';
$_lang['setting_ugm_force_pcl_zip'] = 'Использовать PclZip';
$_lang['setting_ugm_force_pcl_zip_desc'] = 'Принудительное использование PclZip вместо ZipArchive';

$_lang['setting_ugm_cert_path'] = 'Путь к сертификату';
$_lang['setting_ugm_cert_path_desc'] = 'Путь к файлу сертификата SSL в формате .pem; редко необходимо';

/* System Setting Area strings */
$_lang['Download'] = 'Скачать';
$_lang['Form'] = 'Форма';
$_lang['GitHub'] = 'GitHub';
$_lang['Security'] = 'Безопасность';
$_lang['Widget'] = 'Виджет';


/* Used in copyfiles.class.php */
$_lang['ugm_copied'] = 'Скопировано';
$_lang['ugm_to'] = 'в';
$_lang['ugm_files_copied'] = 'Объекты скопированы';

/* Used in downloadfiles.class.php */
$_lang['ugm_downloaded'] = 'Скачано';
$_lang['ugm_download_failed'] = 'Ошибка скачивания';

/* Used in preparesetup.class.php */
$_lang['ugm_no_root_config_core'] = 'Не возможно найти корневой config.core.php';
$_lang['ugm_setup_prepared'] = 'Установка готова';
$_lang['ugm_could_not_write'] = 'Запись невозможна';

/* Used in cleanup.class.php */
$_lang['ugm_deleting_temp_files'] = 'Очистка';
$_lang['ugm_temp_files_deleted'] = 'Очистка завершена (временные файлы удалены)';
