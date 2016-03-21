<sript src="notify.min.js"></sript>

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
<div id="wines">
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
  <fieldset data-id="<?= $comment->id()?>">
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

<script>
  $("#result fieldset:last").data('id');
  $("#result").find('fieldset:last');

</script>



<form action="" method="post" id="monForm" >
  <noscript>
        </form>
        <form action="<?=$NewsinsertComment[$news->id()]?>" method="post"  >

  </noscript>
<?= $form ?>
  <input type ="hidden" name="news" value= "<?= $news->id() ?>" />

  <input type="submit" value="Commenter" />

</form>

<div id="print">
  <form action="" method="post" id="monForm" >
</div>




<script type="text/javascript">



  $(document).ready(function(){
    $('#monForm').on('submit', function() { // This event fires when a button is clicked
      var news = $('[name="news"]').val();
      var auteur = $('[name="auteur"]').val();
      var email = $('[name="email"]').val();
      var contenu = $('[name="contenu"]').val();
      var com =   $("#wines fieldset:last").data('id');


      if (auteur === '' || contenu ==='')   { // If clicked buttons value is all, we post every wine
        alert('Champs vides');
      }

      else {


        $.ajax({ // ajax call starts
              url: 'test.php', // JQuery loads serverside.php
              type: "POST",
              data: 'news=' + news + '&auteur=' + auteur + '&email=' + email + '&contenu=' + contenu + '&com=' + com, // Send value of the clicked button
              dataType: 'json' // Choosing a JSON datatype
            })
            .done(function (data) { // Variable data contains the data we get from serverside
              // If clicked buttons value is red, we post only red wines
              if (data.msg === "Commentaire bien ajouté")
              {
              for (var i in data) {
                if (i != "msg")
                {
                $('#wines').append(commentbuilder(data[i]));

              }}}
              else
              { alert('Email non valide');

              }
            });      }

      return false; // keeps the page from not refreshing
    });
  });


  function commentbuilder(comment)
  {
   return $('<fieldset></fieldset>')
       .attr("data-id", comment.id)
           .append (  $('<legend></legend>')
            .append('Posté par ')
            .append (  $('<strong></strong>')
                .append(comment.auteur))
                .append(' le ')
                .append(comment.date))

       .append($('<p></p>')

           .append(comment.contenu)
       )



  }
</script>



