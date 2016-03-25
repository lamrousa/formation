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
             ?>
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
<script src="//code.jquery.com/jquery.js"></script>
<script src="notify.js"></script>
<script src="comment.js"></script>


</body>
</html>