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
        $this->data['id'] = DB::getInstance()->lastInsertId;
        $this->data['level'] = $this->getLevel();
        $this->data['path'] = $this->getPath();
        $this->update();
    }

    protected function getParent()
    {
        return $this->findByID($this->data['parent_id']);
    }

    protected function getLevel()
    {
        $parent = $this->getParent();
        return $parent ? ++$parent->level : 1;
    }

    protected function getPath()
    {
        $parent = $this->getParent();
        return $parent ? $this->getParent()->path . '.' . $this->data['id'] : $this->data['id'];
    }

    public function getChildren()
    {
        return self::findWhere(['parent_id' => $this->id]);
    }
}