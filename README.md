# binary
Simple implementation for binary tree data structure

----------------------------------------------------

Требования:
PHP 7.1 или выше
MySQL 5.7 или выше
Apache или Nginx любой версии

Прежде всего нужно создать БД и задать в файле config.php настройки подключения к ней.
Для быстрого развертывания вынес создание таблицы ячеек в метод DB::createTable().

Демонстрацию всех публичных методов классов можно проверить в файле index.php