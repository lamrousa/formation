
<?php
if ($msg == true) {
    $mesg="Commentaire bien ajouté";
    $comments=array_merge(array("msg" => $mesg),$comments);
    print json_encode($comments);
}
else
{
    $mesg= array ("msg" =>"Email non valide");
    print json_encode($mesg);
}
/*
<p style="text-align: center;"><?= $msg ?> </p>
 */?>
<?php
/*
$coms = [];

foreach ($comments as $comment) {
    $comn = array('id' => $comment->id(), 'auteur' => $comment['auteur'], 'contenu' => $comment['contenu'], 'date' => $comment['date']);
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
                <a href="<?=  $NewsupdateComment[$comment->id()] ?>">Modifier</a> |
                <a href="<?=  $NewsdeleteComment[$comment->id()] ?>">Supprimer</a>
            <?php } ?>
        </legend>
        <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>

    </fieldset>
    <?php
}*/