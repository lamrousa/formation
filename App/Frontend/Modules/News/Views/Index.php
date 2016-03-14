<?php
foreach ($listeNews as $news)
{



    ?>
    <h2><a href=<?= $Newsshow[$news['id']] ?>><?= $news['titre'] ?></a></h2>
    <p><?= nl2br($news['contenu']) ?></p>
    <?php
}