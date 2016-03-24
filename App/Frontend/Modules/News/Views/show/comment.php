<?php

?>
<fieldset data-id="<?= $Comment->commentId()?>" data-news="<?= $Comment->news()->NewsId() ?>">
    <legend>
        Posté par
        <strong>
            <?php if ($Comment->link('user') == NULL) : ?>
                <?= htmlspecialchars($Comment->auteur()); ?>
            <?php else : ?>
                <a href="<?= $Comment->link('user') ?>">
                    <?= htmlspecialchars($Comment->auteur()) ?>
                </a>
            <?php endif; ?>
        </strong>
        le <?= $Comment->date()->format('d/m/Y à H\hi'); ?>
        <?php

        if ((($Comment->user() == $id) && ($id!=NULL))|| ($user->isAuthenticated())) : ?>
            -
            <a href="<?= $Comment->link('update'); ?>">Modifier</a> |
            <a href="<?= $Comment->link('delete') ?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($Comment->contenu())) ?></p>
</fieldset>