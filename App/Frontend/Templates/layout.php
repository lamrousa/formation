<!DOCTYPE html>
<html>
<head>
    <title>
        <?= isset($title) ? $title : 'Mon super site' ?>
    </title>

    <meta charset="utf-8" />

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
            <li><a href="/">Accueil</a></li>

            <?php if ($user->isAuthenticated()) { ?>
                <li><a href="/admin/">Admin</a></li>
                <li><a href="/admin/news-insert.html">Ajouter une news</a></li>
                <li><a href="/admin/logout.html">Deconnexion</a></li>


            <?php } ?>
            <?php if ($user->isUser() ) {?>
            <li><a href="/logout.html">Deconnexion</a></li>
                <li><a href="/add-news.html">Ajouter une news</a></li>
                <li><a href="/mynews.html">Mes news</a></li>




            <?php } ?>
            <?php if ($user->isAuthenticated() ==false && $user->isUser() ==false ) {?>
                <li><a href="/registration.html">Inscription</a></li>
            <li><a href="/authentification.html">Connexion</a></li>
            <?php } ?>





        </ul>
    </nav>

    <div id="content-wrap">
        <section id="main">
            <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>

            <?= $content ?>
        </section>
    </div>

    <footer></footer>
</div>
</body>
</html>