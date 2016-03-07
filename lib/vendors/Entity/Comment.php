<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 10:07
 */

namespace Entity;


use OCFram\Entity;

class Comment extends Entity

{
protected $news,
        $auteur,
        $contenu,
        $date;

    const AUTEUR_INVALIDE =1;
    const CONTENU_INVALIDE =2;
    public function isValid()
    {
        return !(empty($auteur) || empty($contenu) );

    }

    public function setAuteur($auteur)
    { if (!is_string($auteur) || empty($auteur))
    {
        $this->erreurs[]= self::AUTEUR_INVALIDE;
    }
        $this->auteur = $auteur;
    }

    public function setContenu($contenu)
    { if (!is_string($contenu) || empty($contenu))

    {
        $this->erreurs [] = self::CONTENU_INVALIDE;
    }
        $this->contenu = $contenu;
    }

    public function setDate(\DateTime $date)

    {
        $this->date = $date;
    }

    public function setNews($news)
    {
        $this->news = (int) $news;
    }
    public function news()
    {
        return $this->news;
    }

    public function auteur()
    {
        return $this->auteur;
    }

    public function contenu()
    {
        return $this->contenu;
    }

    public function date()
    {
        return $this->date;
    }


    }