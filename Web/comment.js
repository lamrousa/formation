/**
 * Created by alamrous on 21/03/2016.
 */



    $(document).ready(function(){
     /*  $('#monForm').on('submit', function(e) {
            e.preventDefault();// This event fires when a button is clicked
            var news = $('[name="news"]').val();
            var auteur = $('[name="auteur"]').val();
            var email = $('[name="email"]').val();
            var contenu = $('[name="contenu"]').val();
            var com =   $("#wines fieldset:first").data('id');


            if (auteur === '' || contenu ==='')   { // If clicked buttons value is all, we post every wine
                $("#box2").notify("Champs vides", "error");
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
                        if (data.msg == 1)
                        {
                            for (var i in data) {
                                if (i != "msg")
                                {
                                    $('#top').prepend(commentbuilder(data[i]));
                                    $('#monForm').find("input[type=text], textarea").val("");

                                }}
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                            $("#box2").notify("Commentaire Ajouté", { position:"right",className: "success"});
                        }
                        else if (data.msg == 0)
                        {$("#box2").notify(data.raison, { position:"right",className: "error"});

                        }
                    });      }

            //return false; // keeps the page from not refreshing
        });*/

        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                var com =   $("#wines fieldset:last").data('id');
                var news= $("#wines fieldset:last").data('news');
                var sens = "bottom";
                $.ajax({ // ajax call starts
                    url: 'showDynamic.php', // JQuery loads serverside.php
                    type: "POST",
                    data: 'news=' + news + '&com=' + com +'&sens=' + sens, // Send value of the clicked button
                    dataType: 'json' // Choosing a JSON datatype
                })
                    .done(function (data) { // Variable data contains the data we get from serverside
                        // If clicked buttons value is red, we post only red wines
                        if (data.nb != 0)
                        {
                            for (var i in data) {
                                if (i != "nb")
                                {
                                  (commentbuilder(data[i])).hide().appendTo('#wines').fadeIn("slow");
                                }}
                            $("#wines fieldset:last").notify(data.nb + " Commentaires Affichés", { position:"right",className: "success"});
                        }
                        else if (data.nb == 0)
                        {$("#wines fieldset:last").notify("No more Comments to show", { position:"right",className: "info"});

                        }
                    });

            }
        });
        setInterval(function(){
            var com =   $("#wines fieldset:first").data('id');
            var news= $("#wines fieldset:first").data('news');
            var sens = "top";

            $.ajax({ // ajax call starts
                url: 'showDynamic.php', // JQuery loads serverside.php
                type: "POST",
                data: 'news=' + news + '&com=' + com +'&sens=' + sens, // Send value of the clicked button
                dataType: 'json' // Choosing a JSON datatype
            })
                .done(function (data) { // Variable data contains the data we get from serverside
                    // If clicked buttons value is red, we post only red wines
                    if (data.nb != 0)
                    {
                        for (var i in data) {
                            if (i != "nb")
                            {
                                (commentbuilder(data[i])).hide().prependTo('#top').fadeIn("slow");
                            }}
                        $("#box").notify(data.nb + " Commentaire(s) Ajouté(s)", { position:"right",className: "success"});
                    }

                });

        }, 4000);

        setInterval(function(){
            var count = 0;

            $("#wines fieldset").each(function(){
                var com =  $(this).data('id');
                $.ajax({
                    url: 'check.php', // JQuery loads serverside.php
                    type: "POST",
                    data: 'com=' + com, // Send value of the clicked button
                    dataType: 'json' // Choosing a JSON datatype

                })
                    .done(function(data){
                        if(data.check == 0)
                        {
                            count = count + 1 ;
                       //     $("#box").notify( count + " Commentaire(s) Supprimé(s)", { position:"right",className: "info"});

                            $(" fieldset[data-id=\'"+com + "\']").remove();
                        }

                    })

            }
                )
        },2000);
       $('#monForm').on('submit', function(e) {
            e.preventDefault();// This event fires when a button is clicked
            var news = $('[name="news"]').val();
            var auteur = $('[name="auteur"]').val();
            var email = $('[name="email"]').val();
            var contenu = $('[name="contenu"]').val();
            var com =   $("#wines fieldset:first").data('id');


            if (auteur === '' || contenu ==='')   { // If clicked buttons value is all, we post every wine
                $("#box2").notify("Champs vides", "error");
            }

            else {


               $.ajax({ // ajax call starts
                        url: 'test2.php', // JQuery loads serverside.php
                        type: "POST",
                        data: 'news=' + news + '&auteur=' + auteur + '&email=' + email + '&contenu=' + contenu + '&com=' + com, // Send value of the clicked button
                        dataType: 'json' // Choosing a JSON datatype
                    })

                    .done(function (data) { // Variable data contains the data we get from serverside
                        // If clicked buttons value is red, we post only red wines
                        if (data.msg == 1)
                        {
                            for (var i in data) {
                                if (i != "msg")
                                {
                                    $('#top').prepend(data.valeur);
                                    $('#monForm').find("input[type=text], textarea").val("");

                                }}
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                            $("#box2").notify("Commentaire Ajouté", { position:"right",className: "success"});
                        }
                        else if (data.msg == 0)
                        {$("#box2").notify(data.raison, { position:"right",className: "error"});

                        }
                    });      }

            //return false; // keeps the page from not refreshing
        });

        $(document).ajaxSend(function(ev,req,options){

            if (options.url == 'test2.php')
                $("input[type=submit]").attr('disabled','disabled');
        })
        $(document).ajaxComplete(function(ev,req,options){

            if (options.url == 'test2.php')
                $("input[type=submit]").removeAttr('disabled');
        })




    });


function commentbuilder(comment)
{

if ((comment.etat == 2))
{
    var modiflink ='<a href=\"'+comment.update+'\">Modifier</a> |';
    var dellink ='<a href=\"'+comment.delete+'\">Supprimer</a>';

}else {
     var link = '<span></span>';
     var modiflink = '';
     var dellink = '';
 }
    if (comment.link=='')
    {
        var link = '<span></span>';

    }
    else
    {
        var link ='<a href=\"'+ comment.link +'\"> </a>'  ;

    }

    return $('<fieldset></fieldset>')
        .attr("data-id", comment.id)
        .attr("data-news", comment.news)
        .append (  $('<legend></legend>')
            .append('Posté par ')
            .append($(link)
            .append (  $('<strong></strong>')
                .append(comment.auteur)))
            .append(' le ')
            .append(comment.date)
            .append(modiflink)
            .append(dellink)

        )

        .append($('<p></p>')

            .append(comment.contenu)
        )



}



