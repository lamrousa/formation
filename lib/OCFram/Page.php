<?php
namespace OCFram;

class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];
    protected $links= [];

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var))
        {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()
    {
        if (!file_exists($this->contentFile))
        {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }

        $user = $this->app->user();
        extract($this->vars);
        $this->getLinks();
        var_dump($this->links);
        extract($this->links);
        ob_start();
        require $this->contentFile;

        $content = ob_get_clean();

        ob_start();
        require __DIR__.'/../../App/'.$this->app->name().'/Templates/layout.php';
        return ob_get_clean();
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile))
        {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
    public function getLinks()
    {


                $xml = new \DOMDocument;
                $xml->load(__DIR__.'/../../App/'.$this->app->name().'/Config/routes.xml');

                $routes = $xml->getElementsByTagName('route');
                foreach ($routes as $route)
                {   if (! $route->hasAttribute('vars'))

                    {
                        $module_route=$route->getAttribute('module');
                        $module_route .=$route->getAttribute('action');
                        $url = trim($route->getAttribute('url'),"/");
                        $url = stripslashes ($url);



                        $this->addLinks( $module_route,$url);

                    }
                }
                return NULL;


    }

    public function addLinks($module_action,$url)
    {
        $this->links[$module_action] = $url;
    }
}