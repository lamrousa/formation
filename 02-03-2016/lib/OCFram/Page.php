<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 02/03/2016
 * Time: 17:26
 */

namespace OCFram;


class Page extends ApplicationComponent
{
protected $contentFile ;
protected $vars = [] ;
    public function addVar ($var, $value)
    {
       if ((!is_string($var)) || empty($var) || (is_numeric($var)))
       {
            throw new \InvalidArgumentException ('Le nom de la variable doit être une chaine de caractères') ;
       }
        $this->vars [$var] = $value;


    }

    public function setContentFile($contentFile)
    { if ((!is_string($contentFile)) || empty($contentFile))
    {
        throw new \InvalidArgumentException ('La vue doit etre une chaine de caractere ') ;
    }
        $this->contentFile = $contentFile;

    }
    public function getGeneratedPage()
    {
        if (!file_exists($this->contentFile))
        {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }

        extract($this->vars);

        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
        require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php'; //Pas Compris
        return ob_get_clean();
    }
}
