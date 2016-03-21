<?php
namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
    protected function add(Comment $comment)
    {
        if (filter_var($comment->email(), FILTER_VALIDATE_EMAIL) == true || $comment->email()=='')
        {
        $q = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, email = :email,  date = NOW()');

        $q->bindValue(':news', $comment->news(), \PDO::PARAM_INT);
        $q->bindValue(':auteur', $comment->auteur(), \PDO::PARAM_STR);
        $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);
        $q->bindValue(':email', $comment->email(), \PDO::PARAM_STR);


        $q->execute();

        $comment->setId($this->dao->lastInsertId());
    }
        else throw new \InvalidArgumentException('L\'email n\'est pas dans le  bon format');
    }


    public function delete($id)
    {
        $this->dao->exec('DELETE FROM comments WHERE id = '.(int) $id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comments WHERE news = '.(int) $news);
    }

    public function getListOf($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('
            SELECT id, news, auteur, contenu, email, date
            FROM comments
            WHERE news = :news
            ORDER BY date DESC
             LIMIT 15');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

       if ( $comments = $q->fetchAll())
       {

        foreach ($comments as $comment)
        {
            $comment->setDate(new \DateTime($comment->date()));
            //$comment->clean_msg();
        }

        return $comments;
    }
    return null;
    }
    public function getListOfDistinct($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('
            SELECT   email, auteur
            FROM comments
            WHERE news = :news
            GROUP BY email,auteur');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $q->fetchAll();

        foreach ($comments as $comment)
        {
            $comment->setDate(new \DateTime($comment->date()));
            $comment->clean_msg();
        }

        return $comments;
    }


    protected function modify(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu WHERE id = :id');

        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);

        $q->execute();
    }

    public function get($id)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu , date, email FROM comments WHERE id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetch();
    }

    public function getListByAuthor($author)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu, date
        FROM comments WHERE auteur = :auteur');
        $q->bindValue(':auteur',$author, \PDO::PARAM_STR);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments= $q->fetchAll();
        if ($comments ) {
            foreach ($comments as $comment) {
                //$comment->clean_msg();
            }
        }
        return $comments;

    }
    public function getListByCommentAuthor($author)
    {
        $q=$this->dao->prepare('SELECT news.id AS nid, news.titre, comments.id FROM news INNER JOIN comments ON comments.news=news.id AND comments.auteur = :auteur');
        $q->bindValue(':auteur',$author, \PDO::PARAM_STR);
        $q->execute();


        return $q->fetchAll() ;
    }
    public function getLastId($news)
    {
        $q = $this->dao->prepare('SELECT id FROM comments
        WHERE news = :news
        ORDER BY id
        LIMIT 1
        ');

        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);
        $q->execute();

        return $q->fetchColumn();
    }
    public function getListAfterIdScroll($id,$news)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu , date, email FROM comments WHERE id < :id AND news= :news
ORDER BY id DESC');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);

        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetchAll();
    }
    public function getListAfterIdRefresh($id,$news)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu , date, email FROM comments WHERE id > :id AND news= :news');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);

        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetchAll();
    }

    public function getNewsbyCommentId($id)
    {
        $q=$this->dao->prepare('SELECT news.id FROM news INNER JOIN comments ON comments.news=news.id AND comments.id = :id
ORDER BY id DESC');
        $q->bindValue(':id',$id, \PDO::PARAM_STR);
        $q->execute();


        return $q->fetch() ;
    }


}