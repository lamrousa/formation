<?php
namespace OCFram;

class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];

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
        $this->getLinks();
        var_dump($this->vars);
        extract($this->vars);
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
                        if (strstr($route->getAttribute('url'),'admin'))
                        {
                            $module_route = 'admin'.$module_route;
                        }
                        $url = $route->getAttribute('url');

                        if (strstr($route->getAttribute('url'),'admin') == false  && strlen($url)!= 1)
                        {
                            $url = trim($url, "/");
                        }
                        $url = stripslashes ($url);



                        $this->addVar( $module_route,$url);

                    }
               }



    }
    public function getSpecificLink ($module, $action,array $vars  )
    {
        $xml = new \DOMDocument;
        $xml->load(__DIR__.'/../../App/'.$this->app->name().'/Config/routes.xml');

        $routes = $xml->getElementsByTagName('route');
        foreach ($routes as $route)
        {
            if($route->getAttribute('module') ==$module && $route->getAttribute('action')==$action)
            {
            $module_route=$route->getAttribute('module');
            $module_route .=$route->getAttribute('action');
            if (strstr($route->getAttribute('url'),'admin'))
            {
                $module_route = 'admin'.$module_route;
            }
            $url = $route->getAttribute('url');

            if (strstr($route->getAttribute('url'),'admin') == false  && strlen($url)!= 1)
            {
                $url = trim($url, "/");
            }
            $url = stripslashes ($url);


            $count=substr_count($url,'([0-9]+)');
                $pos= 0;
                $len=strlen('([0-9]+)');

                for ($i = 0; $i<$count; $i++)
                {
                    $pos=strpos($url,'([0-9]+)',$pos);

                    $url=substr_replace($url,$vars[$count-1],$pos,$len);
                }
               return $url;


        }}
        }




}