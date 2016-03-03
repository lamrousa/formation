<?php

namespace Entity ;
use \OCFram\Entity ;
class News extends Entity

{
    protected $titre;
    protected $auteur;
    protected $contenu;
    protected $dateAjout;
    protected $dateModif;

    const AUTEUR_INVALIDE = 1;
    const TITRE_INVALIDE = 2;
    const CONTENU_INVALIDE = 3;

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
    }

    public function setAuteur($auteur)
    {
        if ((!is_string($auteur)) || empty($auteur)) {
            $this->erreurs [] = self::AUTEUR_INVALIDE;
        }
        $this->auteur = $auteur;

    }

    public function setTitre($titre)
    {
        if ((!is_string($titre)) || empty($titre)) {
            $this->erreurs [] = self::TITRE_INVALIDE;
        }
        $this->titre = $titre;
    }

    public function setContenu($contenu)
    {
        if ((!is_string($contenu)) || empty($contenu)) {
            $this->erreurs [] = self::CONTENU_INVALIDE;
        }
        $this->contenu = $contenu;
    }

    public function setdateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;
    }

    public function getAuteur()
    {
        return $this->auteur;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    public function getDateModif()
    {
        return $this->dateModif;
    }

    public function getTitre()
    {
        return $this->titre;
    }
}