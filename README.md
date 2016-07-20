## RSS reader

### Используемые технологии, библиотеки

Клиентская сторона | Серверная сторона
-------------------|-------------------
HTML               | PHP
CSS                | IDNA convert
JavaScript         | PDO
JQuery             | Cron
AJAX               |

В качестве СУБД использовался MySQL.

### Особенности функционирования

* Копия структуры базы данных находится в файле `db_dump.sql`, находящемся в корневой дирректории
* Для подключения к БД в файле `sys-config.php` необходимо указать соответсвующие данные доступа
* Для парсинга фидов cron должен выполнять скрипт по следующему пути: `yourdomain.ru/index.php?get-news=1`
* Все используемые библиотеки либо подключены с удаленнных серверов (как JQuery), либо сохранены в файловой структуре сервиса
* Для вывода ошибок при отладке в файлах контроллеров, находящихся в папке `/application/controllers`, необходимо изменить настройку `display_errors`
* Ошибки обрабатываются собственным обработчиком, часть из которых пишется в файл логов, находящийся в папке `/application/data/logs/`