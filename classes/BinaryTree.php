<?php


namespace classes;


class BinaryTree
{
    /*
     * Построить бинарное дерево заданного размера
     */
    public static function build($depth = 5)
    {
        if (empty(Node::first())) {
            Node::create();
        }

        for ($level = 1; $level < $depth; $level++) {
            $nodes = Node::findWhere(['level' => $level]);

            foreach ($nodes as $node) {
                if (empty($node->getChildren())) {
                    Node::create($node->id, 1);
                    Node::create($node->id, 2);
                }
            }
        }
    }

    /*
     * Очистить бинарное дерево
     */
    public static function destroy()
    {
        $nodes = Node::findAll();
        foreach ($nodes as $node) {
            $node->delete();
        }
    }

    /*
     * Получить всех потомков заданного узла
     */
    public static function getChildrenByNodeID($id)
    {
        $node = Node::findByID($id);
        return Node::findWhere(['path' => $node->path . '.%'], 'LIKE');
    }

    /*
     * Получить всех предков заданного узла
     */
    public static function getParentsByNodeID($id)
    {
        $node = Node::findByID($id);
        $pathElements = explode('.', $node->path);
        $nodes = [];
        foreach ($pathElements as $element) {
            $nodes[] = Node::findByID($element);
        }
        return $nodes;
    }


}