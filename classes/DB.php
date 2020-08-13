<?php


namespace classes;


class DB
{
    protected $dbh;
    protected static $instance;
    public $lastInsertId;

    /*
     * Реализация паттерна Singleton
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /*
     * Подключение к БД
     */
    private function __construct()
    {
        $config = (include __DIR__ . '/../config.php')['db'];
        $this->dbh = new \PDO(
            $config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'],
            $config['username'],
            $config['password']
        );
    }

    /*
     * Для запросов, где ожидается результат от базы (выборк)
     */
    public function query($sql, $data = [], $class)
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);
        return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    /*
     * Просто выполнить запрос (вставка и обновление)
     */
    public function execute($sql, $data = [])
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);
        $this->lastInsertId = $this->dbh->lastInsertId();
    }

    public static function createTable()
    {
        $sql = '
            CREATE TABLE `binary`.`nodes` (
              `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `parent_id` INT(11) NULL,
              `position` INT(11) NULL,
              `path` VARCHAR(12288) NULL,
              `level` INT(11) NULL,
              PRIMARY KEY (`id`),
              UNIQUE INDEX `id_UNIQUE` (`id` ASC));

            INSERT INTO `binary`.`nodes` (`parent_id`, `position`, `path`, `level`) VALUES (\'0\', \'0\', \'1\', \'1\');
        ';
        $sth = self::getInstance()->dbh->prepare($sql);
        $sth->execute();
    }

    public static function dropTable()
    {
        $sql = '
            DROP TABLE `nodes`;
        ';
        $sth = self::getInstance()->dbh->prepare($sql);
        $sth->execute();
    }
}
