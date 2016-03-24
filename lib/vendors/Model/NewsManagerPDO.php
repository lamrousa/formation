<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    protected function add(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO news SET news_auteur = :auteur, news_titre = :titre, news_contenu = :contenu, news_dateAjout = NOW(), news_dateModif = NOW(), news_user = :usser');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':usser', $news->user());

        $requete->execute();

    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM news WHERE news_id = '.(int) $id);
    }

    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT news_id, news_auteur, news_titre, news_contenu, news_dateAjout, news_dateModif FROM news ORDER BY news_id DESC';
        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->dao->query($sql);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();



        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
            $news->clean_msg();
        }

        $requete->closeCursor();

        return $listeNews;
    }

    public function getUnique($id)
    {

        $requete = $this->dao->prepare('SELECT news_id, news_auteur, news_titre, news_contenu, news_dateAjout, news_dateModif FROM news WHERE news_id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if ($news = $requete->fetch())
        {

            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));

           $news->clean_msg();
            return $news;
        }

        return null;
    }

    protected function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET news_auteur = :auteur, news_titre = :titre, news_contenu = :contenu, news_dateModif = NOW() WHERE news_id = :news_id');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':news_id', $news->NewsId(), \PDO::PARAM_INT);

        $requete->execute();
    }
    public function getListByAuthor($id)
    {
        $q=$this->dao->prepare('SELECT news_id, news_auteur, news_titre, news_contenu, news_dateAjout, news_dateModif, news_user FROM news WHERE news_user = :auteur ORDER BY news_id DESC');
       $q->bindValue(':auteur', (int)$id, \PDO::PARAM_STR);
        $q->execute();
       $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $q->fetchAll();



        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
            $news->clean_msg();
        }

        $q->closeCursor();

        return $listeNews;

    }
    public function getIdOfAuthorUsingId($id)
    {
        $q=$this->dao->prepare('SELECT AUC_id,AUC_login,AUC_password,AUC_state,AUC_email,AUC_dateAdd,AUC_dateEnd FROM t_app_userc
 INNER JOIN t_app_authord ON AAD_fk_AUC=AUC_id
 INNER JOIN news ON news.news_id=AAD_fk_news AND news.news_id= :id');

        $q->bindValue(':id', $id, \PDO::PARAM_INT);
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\OutsideUser');
        $q->execute();
        $news=$q->fetch();
        if ($news) {
            //$news->clean_msg();
        }
            return $news;


    }
    public function addnewsUser($user)
    {
        $requete = $this->dao->prepare('INSERT INTO t_app_authord (AAD_fk_AUC,AAD_fk_news)
SELECT  AUC_id,news.news_id
FROM news
INNER JOIN t_app_userc ON news.news_auteur=AUC_login AND AUC_id= :auteur
ORDER BY news.news_id DESC
LIMIT 1');

        $requete->bindValue(':auteur', $user,\PDO::PARAM_INT);

        $requete->execute();
    }
}