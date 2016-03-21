<h1 xmlns="http://www.w3.org/1999/html">Profil de  <?= $log ?></h1>
<h2>Mes News</h2>
<?php

if (empty($listeNews))
{
    ?>  <p> Aucune news postée    <a href="<?=$Newsinsert?>">Ajouter une news </a>
</p> <?php }  ?>
<table>
    <tr><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
    <?php
    foreach ($listeNews as $news)
    {


        echo '<tr><td> <a href="',$Newsshow[ $news->id()],'">', $news['titre'], '</a></td><td>le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="',$Newsupdate[ $news['id']], '"><img src="/images/update.png" alt="Modifier" /></a> <a href="',$Newsdelete[ $news['id']],'"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
    }
    ?>
</table>
<h2>Mes Commentaires</h2>
<?php
if (empty($listeCom))
{
    ?>  <p> Aucun commentaire posté par  <?= $log  ?></p>
<?php }
else {
    ?>
    <table>
        <tr>    <th>Contenu</th><th>Date d'ajout</th> <th>Article Correspondant</th><th>Action</th></tr>
        <?php
        foreach ($listeCom as $com)
        { foreach($listeComnews as $comnew)
        {
            if ((int)$comnew['id'] == $com['id'])
            {


                $titre=htmlentities($comnew['titre']);
                $id = $comnew['nid'];
            }
        }

            ?>
            <tr>
                <td><?= nl2br($com['contenu']) ?></td>

                <td>Le <?=  $com['date']?> </td>

                <td>   <a href="<?=$Newsshow[ $id]?>"> <?= $titre ?></a> </td>
                <td>


                    <a href="<?= $NewsupdateComment[ $com['id']]?>"><img src="/images/update.png" alt="Modifier" /></a> <a href="<?= $NewsdeleteComment[ $com['id']] ?>"><img src="/images/delete.png" alt="Supprimer" /></a></td>
            </tr>

        <?php }
        ?> </table>

<?php }