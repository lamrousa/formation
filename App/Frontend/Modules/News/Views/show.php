

<p>Par <em>
    <?php if ($auteur != NULL ) { ?>
    <a href="<?= $Newsshowauthoruser[$auteur->login()] ?>"><?= $auteur->login() ?></a>
<?php } else { ?>
    <?= $news['auteur'] ?> <?php } ?>
  </em> , le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?></p>
<h2><?= $news['titre'] ?></h2>
<p><?= nl2br($news['contenu']) ?></p>

<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<?php /* <p><a href="commenter-<?= $news['id'] ?>.html">Ajouter un commentaire</a></p> */?>
<div id="result">
<?php
if (empty($comments))
{
  ?>
  <p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
  <?php
}
else
{
foreach ($comments as $comment)
{

  ?>
  <fieldset>
    <legend>
      Posté par <strong>

        <?php   if ($Newsshowuser [$comment['auteur']] !=NULL ) {
        ?>
        <a href="<?= $Newsshowuser[$comment['auteur']]?>">
        <?= htmlspecialchars($comment['auteur']) ?> </a>
        <?php  } else  {echo htmlspecialchars($comment['auteur']);} ?>

      </strong>le <?= $comment['date']->format('d/m/Y à H\hi') ?>
      <?php if ($user->isAuthenticated()) { ?> -
        <a href="<?=  $NewsupdateComment[$comment->id()] ?>">Modifier</a> |
        <a href="<?=  $NewsdeleteComment[$comment->id()] ?>">Supprimer</a>
      <?php } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
  </fieldset>
  <?php
}}

?>


</div>

<p><a href="<?= $NewsinsertComment[$news->id()] ?>">Ajouter un commentaire</a></p>


<button id="Live">Commentaire en direct</button>

<form action="" method="post" id="monForm">
  <h3 id="ICI"></h3>


</form>

<script>
  $(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#Live').click(function() {


      var $data = 'OK'  ;

        // Envoi de la requête HTTP en mode asynchrone
        $.post(
          'showDynamicForm.php', // Le nom du fichier indiqué dans le formulaire
      { nom: 'ok'}, // La méthode indiquée dans le formulaire (get ou post)
         function(data) { // Je récupère la réponse du fichier PHP
            $('#ICI').html(data); // J'affiche cette réponse
          }
        );

    });
  });
</script>


<script>
  $(document).ready(function() {
    // Lorsque je soumets le formulaire
    $('#monForm').on('submit', function(e) {
      e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

      var $this = $(this); // L'objet jQuery du formulaire

      // Je récupère les valeurs
      var news = <?= $news->id()  ?> ;
      var auteur = $('[name="auteur"]').val();
      var email = $('[name="email"]').val();
      var contenu = $('[name="contenu"]').val();

      //var $data = $this.serialize() ;

      // Je vérifie une première fois pour ne pas lancer la requête HTTP
      // si je sais que mon PHP renverra une erreur
      if(auteur === '' ||contenu === '') {
        alert('Les champs doivent êtres remplis');
      }

      else {

        $.post(
            'test.php', // Le nom du fichier indiqué dans le formulaire
            { news:  news, auteur: auteur, email: email, contenu: contenu }, // La méthode indiquée dans le formulaire (get ou post)
            function(data) { // Je récupère la réponse du fichier PHP
              $('#result').html(data); // J'affiche cette réponse
            }
        );
      }
    });
  });
</script>

<script>$('#toto').click(function() {
    $('h2').append(' ***');

  });

</script>
<script></script>
