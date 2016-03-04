<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 11:11
 */

namespace Model;



use Entity\Comment;
use OCFram\Manager;

abstract class CommentsManager extends Manager
{
abstract protected function add(Comment $comment);
    public function save(Comment $comment)
    {
        if ($comment->isValid())
        {
            $comment->isNew() ? $this->add($comment) : $this->modify($comment);
        }
        else
        {
            throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');

        }
    }
   abstract public function getListOf($news);

}