<?php
namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    protected function add(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());

        $requete->execute();
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
    }

    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news ORDER BY id DESC';
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
        }

        $requete->closeCursor();

        return $listeNews;
    }

    public function getUnique($id)
    {

        $requete = $this->dao->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if ($news = $requete->fetch())
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));

            return $news;
        }

        return null;
    }

    protected function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);

        $requete->execute();
    }
    public function getListByAuthor($login)
    {
        $q=$this->dao->prepare('SELECT id, auteur, titre, contenu, dateAjout, dateModif FROM news WHERE auteur = :auteur ORDER BY id DESC');
       $q->bindValue(':auteur', $login, \PDO::PARAM_STR);
        $q->execute();
       $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $q->fetchAll();



        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $q->closeCursor();

        return $listeNews;

    }
    public function getIdOfAuthorUsingId($id)
    {
        $q=$this->dao->prepare('SELECT AUC_id,AUC_login,AUC_password,AUC_state,AUC_email,AUC_dateAdd,AUC_dateEnd FROM t_app_userc
 INNER JOIN t_app_authord ON AAD_fk_AUC=AUC_id
 INNER JOIN news ON news.id=AAD_fk_news AND news.id= :id');

        $q->bindValue(':id', $id, \PDO::PARAM_INT);
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\OutsideUser');
        $q->execute();
        return $q->fetch();


    }
    public function addnewsUser($user)
    {
        $requete = $this->dao->prepare('INSERT INTO t_app_authord (AAD_fk_AUC,AAD_fk_news)
SELECT  AUC_id,news.id
FROM news
INNER JOIN t_app_userc ON news.auteur=AUC_login AND AUC_id= :auteur
ORDER BY news.id DESC
LIMIT 1');

        $requete->bindValue(':auteur', $user,\PDO::PARAM_INT);

        $requete->execute();
    }
}