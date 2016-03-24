<?php
namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
    protected function add(Comment $comment)
    {
        if (filter_var($comment->email(), FILTER_VALIDATE_EMAIL) == true || $comment->email()=='')
        {

        $q = $this->dao->prepare('INSERT INTO comments SET news_fk = :news, auteur = :auteur, contenu = :contenu, email = :email,  date = NOW(),user = :userr');
        $q->bindValue(':news', $comment->news()->newsId(), \PDO::PARAM_INT);
        $q->bindValue(':auteur', $comment->auteur(), \PDO::PARAM_STR);
        $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);
        $q->bindValue(':email', $comment->email(), \PDO::PARAM_STR);
            $q->bindValue(':userr', $comment->user(), \PDO::PARAM_STR);



        $q->execute();

        $comment->setId($this->dao->lastInsertId());
    }
        else throw new \InvalidArgumentException('L\'email n\'est pas dans le  bon format');
    }


    public function delete($id)
    {
        $this->dao->exec('DELETE FROM comments WHERE comment_id = '.(int) $id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comments WHERE news_fk = '.(int) $news);
    }

    public function getListOf($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('
           SELECT *
        FROM comments
         INNER JOIN news ON news.news_id=comments.news_fk
            WHERE news_fk = :news
            ORDER BY date DESC
             LIMIT 15');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();


      $comments=[];
       while($line=$q->fetch())
       {
           $comment=new Comment($line);
           $comment->setNews($line);
           $comments[]=$comment;

       }

        return $comments;

    }
    public function getListOfDistinct($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('
           SELECT *
        FROM comments
         INNER JOIN news ON news.news_id=comments.news_fk
            WHERE news_fk = :news
            GROUP BY email,auteur');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();

        $comments=[];
        while($line=$q->fetch())
        {
            $comment=new Comment($line);
            $comment->setNews($line);
            $comments[]=$comment;

        }

        return $comments;
    }


    protected function modify(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu WHERE comment_id = :id');

        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':id', $comment->commentId(), \PDO::PARAM_INT);

        $q->execute();
    }

    public function get($id)
    {
        $q = $this->dao->prepare('SELECT comment_id, news_fk, auteur, contenu , date, email FROM comments WHERE comment_id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');        $q->execute();

        return $q->fetch();
    }

    public function getListByAuthor($id)
    {
        $q = $this->dao->prepare('SELECT *
        FROM comments
         INNER JOIN news ON news.news_id=comments.news_fk
         WHERE user = :auteur');
        $q->bindValue(':auteur',(int)$id, \PDO::PARAM_INT);
        $q->execute();


        $comments=[];
        while($line=$q->fetch())
        {

         //   $line['date']= new \DateTime($line['date']);
           // $line['news_dateModif']= new \DateTime($line['news_dateModif']);


            $comment=new Comment($line);


            $comment->setNews($line);
            $comments[]=$comment;

        }

        return $comments;

    }
    public function getListByCommentAuthor($id)
    {
        $q=$this->dao->prepare('SELECT * FROM news INNER JOIN comments ON comments.news_fk=news.news_id AND comments.user = :id');
        $q->bindValue(':id',$id, \PDO::PARAM_STR);
        $q->execute();

        $comments=[];
        while($line=$q->fetch())
        {
            $comment=new Comment($line);
            $comment->setNews($line);
            $comments[]=$comment;

        }

        return $comments;
    }
    public function getLastId($news)
    {
        $q = $this->dao->prepare('SELECT comment_id FROM comments
        WHERE news = :news
        ORDER BY comment_id
        LIMIT 1
        ');

        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);
        $q->execute();

        return $q->fetchColumn();
    }
    public function getListAfterIdScroll($id,$news)
    {
        $q = $this->dao->prepare('SELECT *
        FROM comments
         INNER JOIN news ON news.news_id=comments.news_fk
         WHERE comment_id < :id AND news_fk= :news
ORDER BY comment_id DESC
LIMIT 5');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);

        $q->execute();


        $comments=[];
        while($line=$q->fetch())
        {
            $comment=new Comment($line);
            $comment->setNews($line);
            $comments[]=$comment;

        }

        return $comments;
    }
    public function getListAfterIdRefresh($id,$news)
    {
        $q = $this->dao->prepare('SELECT *
        FROM comments
         INNER JOIN news ON news.news_id=comments.news_fk
          WHERE comment_id > :id AND news_fk= :news ORDER BY comment_id DESC ');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->bindValue(':news', (int) $news, \PDO::PARAM_INT);

        $q->execute();


        $comments=[];
        while($line=$q->fetch())
        {
            $comment=new Comment($line);
            $comment->setNews($line);
            $comments[]=$comment;

        }

        return $comments;
    }

    public function getNewsbyCommentId($id)
    {
        $q=$this->dao->prepare('SELECT news.news_id FROM news INNER JOIN comments ON comments.news_fk=news.news_id AND comments.comment_id = :id
ORDER BY comment_id DESC');
        $q->bindValue(':id',$id, \PDO::PARAM_STR);
        $q->execute();


        return $q->fetch() ;
    }


}