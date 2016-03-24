<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
  <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
  <?php
  foreach ($listeNews_a as $news)
  {
    echo '<tr>
<td>', $news->auteur(), '</td>
<td><a href="',$news->link('show'),'">', $news->titre(), '</a></td>
<td>le ', $news->dateAjout()->format('d/m/Y à H\hi'), '</td>
<td>', ($news->dateAjout() == $news->dateModif() ? '-' : 'le '.$news->dateModif()->format('d/m/Y à H\hi')), '</td>
<td><a href="',$news->link('update'),'"><img src="/images/update.png" alt="Modifier" /></a> <a href="',$news->link('delete'),'"><img src="/images/delete.png" alt="Supprimer" /></a></td>
  </tr>', "\n";
  }
  ?>
</table>