<?php

foreach ($listeNews as $news)
{
    ?>
    <?php $show = str_replace('([0-9]+)',$news['id'],$Newsshow) ?>
    <h2><a href=<?= $show ?>><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}