<h1>Profil de  <?= $log ?></h1>
<h2>Mes News</h2>
<?php

if (empty($listeNews))
{
    ?>  <p> Aucune news postée    <a href="/add-news.html">Ajouter une news </a>
    </p> <?php }  ?>
<table>
  <tr><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
  <?php
  foreach ($listeNews as $news)
  {
      $update = str_replace('([0-9]+)',$news['id'],$Newsupdate) ;
      $delete = str_replace('([0-9]+)',$news['id'],$Newsdelete) ;


      echo '<tr><td>', $news['titre'], '</td><td>le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="',$update, '"><img src="/images/update.png" alt="Modifier" /></a> <a href="',$delete,'"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
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
foreach ($listeCom as $com)
{ foreach($listeComnews as $comnew)
{
  if ((int)$comnew['id'] == $com['id'])
  {


      $titre=$comnew['titre'];
  }
}

    ?>
<table>
    <tr><th>Contenu</th><th>Date d'ajout</th> <th>Article Correspondant</th><th>Action</th></tr>
    <td><?= nl2br($com['contenu']) ?></td>

    <td>Le <?=  $com['date']?> </td>
    <?php $show = str_replace('([0-9]+)',$news['id'],$Newsshow);
    $comupdate = str_replace('([0-9]+)',$com['id'],$NewsupdateComment);
    $comdelete=str_replace('([0-9]+)',$com['id'],$NewsdeleteComment);
            ?>
    <td>   <a href="<?=$show?>"> <?= $titre ?></a> </td>
    <td>


        <a href="<?= $comupdate?>"><img src="/images/update.png" alt="Modifier" /></a> <a href="<?= $comdelete ?>"><img src="/images/delete.png" alt="Supprimer" /></a></td>
</table>

<?php }}