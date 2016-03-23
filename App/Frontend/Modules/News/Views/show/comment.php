<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 22/03/2016
 * Time: 16:57
 */
/**
 * @var \Entity\Comment $Comment
 */
?>
<fieldset data-id="<?=$Comment['id']?>" data-news="<?=$Comment['news']?>">
    <legend>
        Posté par
        <strong>
            <?php if ($Comment->link('user') ==NULL ) : ?>
                <?=htmlspecialchars($Comment['auteur']); ?>
            <?php else : ?>
                <a href="<?=$Comment->link('user')?>">
                    <?= htmlspecialchars($Comment['auteur']) ?>
                </a>
            <?php endif; ?>
        </strong>
        le <?= $Comment['date']->format('d/m/Y à H\hi') ; ?>
        <?php
        if (($Comment->user()==$id) || ($user->isAuthenticated())) :  ?>
            -
            <a href="<?=$Comment->link('update');?>">Modifier</a> |
            <a href="<?=$Comment->link('delete')?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($Comment['contenu'])) ?></p>
</fieldset>