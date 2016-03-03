<?php
if ((!isset($_GET['app'])) && (file_exists('App/'.$_GET['app'])))
{
    $nom = $_GET['app'];
    /*SplClassLoader permettant l'autoload*/
    require __DIR__ . '/../lib/OCFram/SplClassLoader.php';

    /* Auto Load de*/
    $splOCFramload = new SplClassLoader('OCFram', __DIR__ . '/../lib');
    $splOCFramload->register();

    $splAppLoad = new SplClassLoader('App', __DIR__ . '/..');
    $splAppLoad->register();

    $splModelLoad = new SplClassLoader('Model', __DIR__ . '/../lib/vendors');
    $splModelLoad->register();

    $splEntityLoad = new SplClassLoader('Entity', __DIR__ . '/../lib/vendors');
    $splEntityLoad->register();

    $AppliClassName = 'App \\ ' . $nom . '\\' . $nom . 'Application';
    $Application = new $AppliClassName;
    $Application->run();
}
else
{
    $nom= 'FrontEnd' ; //Valeur par défaut
}



