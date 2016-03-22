
<p>Par <em>
    <?php if ($auteur != NULL ) { ?>
    <a href="<?= $news['link']['show'] ?>"><?= $auteur->login() ?></a>
<?php } else { ?>
    <?= $news['auteur'] ?> <?php } ?>
  </em> , le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2 id="box"><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<?php /* <p><a href="commenter-<?= $news['id'] ?>.html">Ajouter un commentaire</a></p> */?>

<form action="" method="post" id="monForm" >
  <noscript>
</form>
<form action="<?=$news->link('insertComment')?>" method="post"  >

  </noscript>
  <?= $form ?>
  <input type ="hidden" name="news" value= "<?= $news->id() ?>" />
  <i>  <i><p align="right"><span style="color: #b82720"> * : Champ Obligatoire</span></p></i></i>

  <input  type="submit" value="Commenter" />

</form>
<div id="box2"> </div>
<div id="print"></div>

<div id="wines">
  <div id="top"></div>
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
  <fieldset data-id="<?= $comment->id()?>" data-news="<?=$news['id']?>">
    <legend>
      Posté par <strong>

        <?php   if ($comment ['link']['user'] !=NULL ) {
        ?>
        <a href="<?=$comment ['link']['user']?>">
        <?= htmlspecialchars($comment['auteur']) ?> </a>
        <?php  } else  {echo htmlspecialchars($comment['auteur']);} ?>

      </strong>le <?= $comment['date']->format('d/m/Y à H\hi') ?>
      <?php if ($user->isAuthenticated()) { ?> -
        <a href="<?=  $comment->link('update') ?>">Modifier</a> |
        <a href="<?= $comment['link']['delete'] ?>">Supprimer</a>
      <?php } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
  </fieldset>
  <?php
}
}


//foreach ($Comment_a as $Comment) :
//  include(__DIR__.'/show/comment.php');
//endforeach;

?>



</div>

<form action="" method="post" id="testForm" >
  <label>Test</label><input type="text" name="test" />
  <input type="submit" value="Cliquer"/>
</form>












