<?php
$showauthoruser = str_replace('([0-9]+)',$news['id'],$Newsshowauthoruser) ;

 ?>

<p>Par <em><a href="<?= $showauthoruser ?>"><?= $news['auteur'] ?></em> </a>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
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
  $showuser = str_replace('([0-9]+)',$comment['id'],$Newsshowuser) ;
  $updateComment = str_replace('([0-9]+)',$comment['id'],$NewsupdateComment) ;
  $deleteComment = str_replace('([0-9]+)',$comment['id'],$NewsdeleteComment) ;
  ?>
  <fieldset>
    <legend>
      Posté par <strong><a href="<?= $showuser ?>"><?= htmlspecialchars($comment['auteur']) ?></strong> </a>le <?= $comment['date']->format('d/m/Y à H\hi') ?>
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