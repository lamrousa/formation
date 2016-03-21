/**
 * Created by alamrous on 21/03/2016.
 */



    $(document).ready(function(){
        $('#monForm').on('submit', function(e) {
            e.preventDefault();// This event fires when a button is clicked
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

            //return false; // keeps the page from not refreshing
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
