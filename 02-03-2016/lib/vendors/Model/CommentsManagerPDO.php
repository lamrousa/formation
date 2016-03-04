<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 11:23
 */

namespace Model;


use Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
protected function add(Comment $comment)
{
    $query = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu= :contenu, date = NOW()');
    $query->bindValue(':news',$comment->news(), \PDO::PARAM_INT);
    $query->bindValue(':auteur',$comment->auteur());
    $query->bindValue(':contenu', $comment->contenu());
    $query->execute();
    $comment->setId($this->dao->lastInsertId());

}
    public function getListOf($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException ('L\'identifiant de la news passé doit être un nombre entier valide');
        }
        $query = $this->dao->prepare('SELECT id, news, auteur, contenu, date FROM comments WHERE news = :news');
        $query->bindValue(':news', $news, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS |\PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $query->fetchAll();
        foreach($comments as $comment)
        {
            $comment->setDate(new \DateTime($comment->date()));
        }
        return $comments;
    }

}