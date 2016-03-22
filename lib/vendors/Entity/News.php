<?php
namespace Entity;

use \OCFram\Entity;

class News extends Entity
{
  protected $auteur,
      $titre,
      $contenu,
      $dateAjout,
      $dateModif,
      $user,
      $link=[];


  const AUTEUR_INVALIDE = 1;
  const TITRE_INVALIDE = 2;
  const CONTENU_INVALIDE = 3;

  public function isValid()
  {
    return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
  }


  // SETTERS //

  public function setAuteur($auteur)
  {
    if (!is_string($auteur) || empty($auteur))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }

    $this->auteur = $auteur;
  }

  public function setTitre($titre)
  {
    if (!is_string($titre) || empty($titre))
    {
      $this->erreurs[] = self::TITRE_INVALIDE;
    }

    $this->titre = $titre;
  }

  public function setContenu($contenu)
  {
    if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }

    $this->contenu = $contenu;
  }

  public function setDateAjout(\DateTime $dateAjout)
  {
    $this->dateAjout = $dateAjout;
  }

  public function setDateModif(\DateTime $dateModif)
  {
    $this->dateModif = $dateModif;
  }

  // GETTERS //

  public function auteur()
  {
    return $this->auteur;
  }

  public function titre()
  {
    return $this->titre;
  }

  public function contenu()
  {
    return $this->contenu;
  }

  public function dateAjout()
  {
    return $this->dateAjout;
  }

  public function dateModif()
  {
    return $this->dateModif;
  }
  public function clean_msg()
  {  $this->auteur= htmlentities($this->auteur);
    $this->titre= htmlentities($this->titre);
    $this->contenu= htmlentities($this->contenu);

  }
  public function setLink($key,$AUC_link)
  {
    $this->link[$key] = $AUC_link;
  }


  public function link($key)
  {
    return $this->link[$key];
  }

  public function setUser($user)
  {
    $this->user = $user;
  }

  public function user()
  {
    return $this->user;
  }
}