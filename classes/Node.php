<?php


namespace classes;


class Node extends Model
{
    protected const TABLE = 'nodes';
    protected $data = [];

    public static function create($parent_id = 0, $position = 0)
    {
        $node = new self();
        $node->parent_id = $parent_id;
        $node->position = $position;
        $node->save();
        return $node;
    }

    public static function first()
    {
        return self::findWhere(['level' => 1])[0];
    }

    public function save()
    {
        $this->insert();
        $this->id = DB::getInstance()->lastInsertId;
        $this->level = $this->getLevel();
        $this->path = $this->getPath();
        $this->update();
    }

    protected function getParent()
    {
        return $this->findByID($this->parent_id);
    }

    protected function getLevel()
    {
        $parent = $this->getParent();
        return $parent ? ++$parent->level : 1;
    }

    protected function getPath()
    {
        $parent = $this->getParent();
        return $parent ? $this->getParent()->path . '.' . $this->id : $this->id;
    }

    public function getChildren()
    {
        return self::findWhere(['parent_id' => $this->id]);
    }
}