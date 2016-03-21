<sript src="notify.min.js"></sript>

<p>Par <em>
    <?php if ($auteur != NULL ) { ?>
    <a href="<?= $Newsshowauthoruser[$auteur->login()] ?>"><?= $auteur->login() ?></a>
<?php } else { ?>
    <?= $news['auteur'] ?> <?php } ?>
  </em> , le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<?php /* <p><a href="commenter-<?= $news['id'] ?>.html">Ajouter un commentaire</a></p> */?>
<div id="wines">
<?php
if (empty($comments))
{
  ?>
  <p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
  <?php
}
else
{
foreach ($comments as $comment)
{

  ?>
  <fieldset data-id="<?= $comment->id()?>">
    <legend>
      Posté par <strong>

        <?php   if ($Newsshowuser [$comment['auteur']] !=NULL ) {
        ?>
        <a href="<?= $Newsshowuser[$comment['auteur']]?>">
        <?= htmlspecialchars($comment['auteur']) ?> </a>
        <?php  } else  {echo htmlspecialchars($comment['auteur']);} ?>

      </strong>le <?= $comment['date']->format('d/m/Y à H\hi') ?>
      <?php if ($user->isAuthenticated()) { ?> -
        <a href="<?=  $NewsupdateComment[$comment->id()] ?>">Modifier</a> |
        <a href="<?=  $NewsdeleteComment[$comment->id()] ?>">Supprimer</a>
      <?php } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
  </fieldset>
  <?php
}}

?>



</div>

<script>
  $("#result fieldset:last").data('id');
  $("#result").find('fieldset:last');

</script>



<form action="" method="post" id="monForm" >
  <noscript>
        </form>
        <form action="<?=$NewsinsertComment[$news->id()]?>" method="post"  >

  </noscript>
<?= $form ?>
  <input type ="hidden" name="news" value= "<?= $news->id() ?>" />

  <input type="submit" value="Commenter" />

</form>

<div id="print">
  <form action="" method="post" id="monForm" >
</div>






