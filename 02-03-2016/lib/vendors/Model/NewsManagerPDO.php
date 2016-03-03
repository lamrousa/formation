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
}