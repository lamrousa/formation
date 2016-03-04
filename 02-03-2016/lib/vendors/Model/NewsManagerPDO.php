<?php

namespace Model;

use \Entity\News;

class NewsManagerPDO extends NewsManager
{
    public function getList($debut =-1, $limite=-1)
    {
        $sql='SELECT id,auteur,contenu,dateAjout,dateModif FROM news ORDER BY id DESC';
        if ($debut != -1 || $limite !=-1)
        {
            $sql=$sql.'LIMIT'.(int)$limite.'OFFSET'.(int)$debut;

        }
        $query = $this->dao->query ($sql);
        $query->setFetchMode (\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
        $listeNews = $query->fetchAll();

        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \Datetime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }
        $query->closeCursor();


        return $listeNews;

    }
    public function getUnique($id)
    {
        $sql='SELECT id, auteur, contenu, dateAjout, dateModif FROM news WHERE id = :id ';
        $query= $this->dao->prepare($sql) ;
        $query->bindValue(':id',(int) $id, \PDO::PARAM_INT);
        $query->execute();

        $query->setFetchMode (\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
        if ($news = $requete->fetch())
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));

            return $news;
        }

        return null;
    }
    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }
    public function add(News $news)
    {
        $requete = $this->dao->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
        $q->bindvalue(':titre', $news->titre());
        $q->bindvalue(':auteur', $news->auteur());
        $q->bindvalue(':contenu', $news->contenu());

        $q->execute();

    }
    public function modify(News $news)
    {
        $requete = $this->dao->prepare('UPDATE news SET auteur = :auteur, titre = :=titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');
        $requete->bindValue(':titre', $news->titre());
        $requete->bindValue(':auteur', $news->auteur());
        $requete->bindValue(':contenu', $news->contenu());
        $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);

        $requete->execute();
    }
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM news WHERE id = '.(int) $id);
    }
    }
