<?php

namespace Model ;

use OCFram\Manager;
use \Entity\News;

abstract class NewsManager extends Manager

{
    abstract public function getList($debut=-1, $limite=-1);
    abstract public function getUnique($id);
    abstract public function count();
    abstract protected function add(News $news);
    abstract protected function modify(News $news);
    abstract protected function delete($id);

    /**
     * Méthode permettant d'enregistrer une news.
     * @param $news News la news à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(News $news)
    {
        if ($news->isValid())
        {
            $news->isNew() ? $this->add($news) : this->modify($news);
        }
        else
        {
            throw new \RuntimeException('La news doit être validée pour être enregistrée');
        }
    }



}