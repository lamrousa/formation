<?php
namespace Entity;

use \OCFram\Entity;

class News extends Entity
{
  protected $news_auteur,
      $news_titre,
      $news_contenu,
      $news_dateAjout,
      $news_dateModif,
      $news_user,
    $news_id,
      $news_link=[];


  const AUTEUR_INVALIDE = 1;
  const TITRE_INVALIDE = 2;
  const CONTENU_INVALIDE = 3;

  public function isValid()
  {
    return !(empty($this->news_auteur) || empty($this->news_titre) || empty($this->news_contenu));
  }


  // SETTERS //

  public function setAuteur($auteur)
  {
    if (!is_string($auteur) || empty($auteur))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }

    $this->news_auteur = $auteur;
  }

  public function setTitre($titre)
  {
    if (!is_string($titre) || empty($titre))
    {
      $this->erreurs[] = self::TITRE_INVALIDE;
    }

    $this->news_titre = $titre;
  }

  public function setContenu($contenu)
  {
    if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }

    $this->news_contenu = $contenu;
  }

  public function setDateAjout(\DateTime $dateAjout)
  {
    $this->news_dateAjout = $dateAjout;
  }

  public function setDateModif(\DateTime $dateModif)
  {
    $this->news_dateModif = $dateModif;
  }

  // GETTERS //
  public function setNewsId($news_id)
  {
    $this->news_id = $news_id;
  }

  public function NewsId()
  {
    return $this->news_id;
  }
  public function auteur()
  {
    return $this->news_auteur;
  }

  public function titre()
  {
    return $this->news_titre;
  }

  public function contenu()
  {
    return $this->news_contenu;
  }

  public function dateAjout()
  {
    return $this->news_dateAjout;
  }

  public function dateModif()
  {
    return $this->news_dateModif;
  }
  public function clean_msg()
  {  $this->news_auteur= htmlentities($this->news_auteur);
    $this->news_titre= htmlentities($this->news_titre);
    $this->news_contenu= htmlentities($this->news_contenu);

  }
  public function setLink($key,$AUC_link)
  {
    $this->news_link[$key] = $AUC_link;
  }


  public function link($key)
  {
    return $this->news_link[$key];
  }
  public function getLink()
  {
    return $this->news_link;
  }

  public function setUser($user)
  {
    $this->news_user = $user;
  }

  public function user()
  {
    return $this->news_user;
  }
  public function isNewsNew()
  {
    return empty($this->news_id);
  }
}