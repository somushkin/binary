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

}
