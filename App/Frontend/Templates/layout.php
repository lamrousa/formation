<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
</head>

<body>
<div id="wrap">
    <header>
        <h1><a href="/">Mon super site</a></h1>
        <p>Comment ça, il n'y a presque rien ?</p>
    </header>


   <nav>
        <ul>
            <?php
            echo $menu;
            /*

            <?php if ($user->isAuthenticated() == false ) {?>
            <li><a href="/">Accueil</a></li>
            <?php } else {
            ?>
            <li><a href="<?=$adminNewsindex?>">Accueil</a></li>

            <?php }
            if ($user->isAuthenticated()) { ?>
                <li><a href="<?=$adminNewsindex?>">Admin</a></li>
                <li><a href="<?=$adminNewsinsert?>">Ajouter une news</a></li>
                <li><a href="<?=$adminConnexionlogout?>">Deconnexion</a></li>


            <?php }  ;?>
            <?php if ($user->isUser() ) {?>
                <li><a href="<?=$Connexionlogout?>">Deconnexion</a></li>
                <li><a href="<?=$Newsinsert?>">Ajouter une news</a></li>
                <li><a href="<?=$Newsmynews?>">Mes news</a></li>




            <?php } if (isset($Connexionsignup) && isset($Connexionlogin)) {?>
                <?php if ($user->isAuthenticated() ==false && $user->isUser() ==false ) {?>
                    <li><a href="<?=$Connexionsignup?>">Inscription</a></li>
                    <li><a href="<?=$Connexionlogin?>">Connexion</a></li>
                <?php } }?>





     */ ?>    </ul>
   </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
            <script src="//code.jquery.com/jquery.js"></script>


            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>

</body>
</html>