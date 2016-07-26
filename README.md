## RSS reader

### Используемые технологии, библиотеки

Клиентская сторона | Серверная сторона
-------------------|-------------------
HTML               | PHP
CSS                | IDNA convert
JavaScript         | PDO
JQuery             | Cron
AJAX               | MySQL

Для ускорения разработки применялсиь

Инструмент         |
-------------------|
SCSS               |

### Особенности функционирования

* Копия структуры базы данных находится в файле `db_dump.sql`, находящемся в корневой дирректории
* Для подключения к БД в файле `sys-config.php` необходимо указать соответсвующие данные доступа
* Для парсинга фидов, cron должен выполнять скрипт по следующему пути: `yourdomain.ru/index?get-news=1`
* Для удаления прочитанных новостей, cron должен выполнять скрипт по следующему пути: `yourdomain.ru/index?delete-read-news=1`
* В `.htaccess` в строках 14 и 15 нужно указать Ваш домен
* Все используемые библиотеки либо подключены с удаленнных серверов (как JQuery), либо сохранены в файловой структуре сервиса
* Для вывода ошибок при отладке, в файлах контроллеров, находящихся в папке `/application/controllers`, необходимо изменить настройку `display_errors`
* Ошибки обрабатываются собственным обработчиком, часть из которых пишется в файл логов, находящийся в папке `/application/data/logs/`
