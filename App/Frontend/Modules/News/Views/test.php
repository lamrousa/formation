
<p style="text-align: center;"><?= $msg ?> </p>
<?php
foreach ($comments as $comment)
{

    ?>
    <fieldset>
        <legend>
            Posté par <strong><?= htmlspecialchars($comment['auteur']) ?>

            </strong>
            le <?= $comment['date']->format('d/m/Y à H\hi') ?>
            <?php if ($user->isAuthenticated()) { ?> -
                <a href="<?=  $NewsupdateComment[$comment->id()] ?>">Modifier</a> |
                <a href="<?=  $NewsdeleteComment[$comment->id()] ?>">Supprimer</a>
            <?php } ?>
        </legend>
        <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>

    </fieldset>
    <?php
}