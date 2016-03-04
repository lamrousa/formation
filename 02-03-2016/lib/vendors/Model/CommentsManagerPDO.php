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
    public function modify(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu WHERE id = :id');
        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);

        $q->execute();    }
    public function get($id)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu FROM comments WHERE id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetch();
    }
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM comments WHERE id ='.(int)$id);
    }
    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comments WHERE news= '. (int)$news);
    }
}