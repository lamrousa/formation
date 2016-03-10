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
    echo '<tr><td>', $news['titre'], '</td><td>le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td><td><a href="news-update-', $news['id'], '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $news['id'], '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
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
    <td>   <a href="news-<?= $com['news'] ?>.html"> <?= $titre ?></a> </td>
    <td>


        <a href="comment-update-<?= $com['id'] ?>.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="comment-delete-<?= $com['id'] ?>.html"><img src="/images/delete.png" alt="Supprimer" /></a></td>
</table>

<?php }}