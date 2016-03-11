

<p>Par <em>
    <?php if ($auteur != NULL ) { $showauthoruser = str_replace('([0-9]+)',$auteur->id(),$Newsshowauthoruser) ;?>
    <a href="<?= $showauthoruser ?>"><?= $auteur->login() ?></a>
<?php } else { ?>
    <?= $news['auteur'] ?> <?php } ?>
  </em> , le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<?php /* <p><a href="commenter-<?= $news['id'] ?>.html">Ajouter un commentaire</a></p> */?>

<?php
if (empty($comments))
{
  ?>
  <p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
  <?php
}

foreach ($comments as $comment)
{

  $updateComment = str_replace('([0-9]+)',$comment['id'],$NewsupdateComment) ;
  $deleteComment = str_replace('([0-9]+)',$comment['id'],$NewsdeleteComment) ;
  ?>
  <fieldset>
    <legend>
      Posté par <strong>
        <?php
        foreach($authors as $auth)
        {
          if ($auth->login()==$comment['auteur'])

          { $nom= $auth; break;}
          else {$nom=NULL;}
    }
        ?>
        <?php if ($nom !=NULL ) {   $showuser = str_replace('([0-9]+)',$nom->id(),$Newsshowuser);
        ?>
        <a href="<?= $showuser ?>">
        <?= htmlspecialchars($comment['auteur']) ?> </a>
        <?php  } else  {echo htmlspecialchars($comment['auteur']);} ?>

      </strong>le <?= $comment['date']->format('d/m/Y à H\hi') ?>
      <?php if ($user->isAuthenticated()) { ?> -
        <a href="<?= $updateComment ?>">Modifier</a> |
        <a href="<?= $deleteComment ?>">Supprimer</a>
      <?php } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
  </fieldset>
  <?php
}
$insertComment = str_replace('([0-9]+)',$news['id'],$NewsinsertComment) ;

?>

<p><a href="<?= $insertComment ?>">Ajouter un commentaire</a></p>