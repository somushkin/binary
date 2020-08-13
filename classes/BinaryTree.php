<?php


namespace classes;


class BinaryTree
{
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

    public static function destroy()
    {
        $nodes = Node::findAll();
        foreach ($nodes as $node) {
            $node->delete();
        }
    }
}