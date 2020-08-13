<?php


namespace classes;


class DB
{
    protected $dbh;
    protected static $instance;
    public $lastInsertId;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $config = (include __DIR__ . '/../config.php')['db'];
        $this->dbh = new \PDO(
            $config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'],
            $config['username'],
            $config['password']
        );
    }

    public function query($sql, $data = [], $class)
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);
        return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function execute($sql, $data = [])
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);
        $this->lastInsertId = $this->dbh->lastInsertId();
    }

}
