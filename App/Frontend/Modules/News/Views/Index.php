<?php
if ($listeNews)
{

foreach ($listeNews as $news)
{



    ?>

    <h2><a href=<?= $news->link('show') ?>><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}}
else
echo '<p align="center">Aucune news enregistrée </p>';