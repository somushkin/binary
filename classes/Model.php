<?php


namespace classes;


abstract class Model
{

    protected const TABLE = '';
    protected $data = [];

    public static function findAll()
    {
        $sql = 'SELECT * FROM ' . static::TABLE;
        return DB::getInstance()->query($sql, []);
    }

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM nodes WHERE id=:id';
        return DB::getInstance()->query($sql, [':id' => $id])[0];
    }

    /**
     * Сохранение записи в БД:
     * если передан id - пробуем обновить запись, в ином случае вставляем новую
     */
    public function save()
    {
        try {
            if (!isset($this->data['id'])) {
                $this->insert();
                $this->data['id'] = DB::getInstance()->lastInsertId;
                $this->data['level'] = $this->getLevel();
                $this->data['path'] = $this->getPath();
            }
            $this->update();
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    protected function insert()
    {
        $keys = array_keys($this->data);
        $keysStr = implode(',', $keys);
        $values = [];
        foreach ($this->data as $key => $value) {
            $values[':' . $key] = $value;
        }
        $valuesStr = implode(',', array_keys($values));
        $sql = 'INSERT INTO '.static::TABLE.' ('.$keysStr.') VALUES ('.$valuesStr.')';

        DB::getInstance()->execute($sql, $values);
    }

    protected function update()
    {
        $values = [':id' => $this->data['id']];
        $pairs = [];

        foreach ($this->data as $key => $value) {
            if ($key == 'id') {
                continue;
            }

            $values[':' . $key] = $value;
            $pairs[] = $key . '=:' . $key;
        }

        $sql = 'UPDATE '.static::TABLE.' SET '.implode(',', $pairs).' WHERE id=:id';

        DB::getInstance()->execute($sql, $values);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

}