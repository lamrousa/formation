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
            <?php if ($Comment['auteur'] !=NULL ) : ?>
                <?=htmlspecialchars($Comment['auteur']); ?>
            <?php else : ?>
                <a href="<?=$Comment['User']['profillink']?>">
                    <?= htmlspecialchars($Comment['User']['login']) ?>
                </a>
            <?php endif; ?>
        </strong>
        le <?= $Comment['date']->format('d/m/Y à H\hi') ; ?>
        <?php if (isset($Comment['link'])) : ?>
            -
            <a href="<?=$Comment['link']['update'];?>">Modifier</a> |
            <a href="<?=$Comment['link']['delete']?>">Supprimer</a>
        <?php endif; ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($Comment['contenu'])) ?></p>
</fieldset>