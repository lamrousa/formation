<?php
?><h1> Les News postées par <?= $auteur  ?></h1> <?php
if (empty($listnews))
{
    ?>  <p> Aucune news postée par  <?= $auteur  ?></p>
<?php }
foreach ($listnews as $news)
{
    ?>
    <h2><a href="news-<?= $news['id'] ?>.html"><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}?>
    <h1> Les Commentaires postés par <?= $auteur  ?></h1> <?php
if (empty($listcom))
{
    ?>  <p> Aucun commentaire posté par  <?= $auteur  ?></p>
<?php }
else {
foreach ($listcom as $com)
{ ?>


    <h2>Commentaire de <?= $auteur  ?> Le <?=  $com['date']?> </h2>
    <p><?= nl2br($com['contenu']) ?></p>
    <p>   <a href="news-<?= $com['news'] ?>.html"> Voir l'article correspondant </a> </p>


    <?php
}}?>