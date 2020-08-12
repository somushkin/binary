<?php


namespace classes;


class Node extends Model
{
    protected const TABLE = 'nodes';
    protected $data = [];

    public function __construct($parent_id = 0, $position = 0)
    {
        $this->data['parent_id'] = $parent_id;
        $this->data['position'] = $position;
    }

    public function getParent()
    {
        return static::findByID($this->data['parent_id']);
    }

    public function getLevel()
    {
        return ++$this->getParent()->level;
    }

    public function getPath()
    {
        return $this->getParent()->path . '.' . $this->data['id'];
    }
}