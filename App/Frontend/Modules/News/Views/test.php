
<?php
/*
if ($msg == true) {
    $mesg=1;
    $comments=array_merge(array("msg" => $mesg),$comments);
    print json_encode($comments);
}
else
{
    $mesg= array ("msg" =>0, "raison" => $raison);
    print json_encode($mesg);
}
/*
<p style="text-align: center;"><?= $msg ?> </p>
 */?>
<?php
/*
$coms = [];

foreach ($comments as $comment) {
    $comn = array('id' => $comment->commentId(), 'auteur' => $comment['auteur'], 'contenu' => $comment['contenu'], 'date' => $comment['date']);
    $coms[]= $comn;
}
echo json_encode($coms);

   /* ?>
    <fieldset>
        <legend>
            Posté par <strong><?= htmlspecialchars($comment['auteur']) ?>

            </strong>
            le <?= $comment['date']->format('d/m/Y à H\hi') ?>
            <?php if ($user->isAuthenticated()) { ?> -
                <a href="<?=  $comment->link('update') ?>">Modifier</a> |
                <a href="<?= $comment->->link('delete') ?>">Supprimer</a>
            <?php } ?>
        </legend>
        <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>

    </fieldset>
    <?php
}*/