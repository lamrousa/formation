<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 22/03/2016
 * Time: 15:20
 */

namespace OCFram;


class HtmlComment
{
protected $fieldset='<fieldset ';
    protected $legend= '<legend>';
    protected $attr='';
    protected $content='<p>';


    public function setAttr(array $tab)
    {
        foreach($tab as $key => $at)
        {
            $this->attr.=$key .'="'.$at.'" ';
        }
        $this->fieldset.=$this->attr.'>';
    }

    public function setLegend($authorlink,$author,$date,$ulink,$dlink,$state)
    {
        $update= '';
        $delete = '';
        if ($authorlink=='')
        {
            $link = setBalise('span',$author);
        }
        else {
            $link = $this->setLink($authorlink, $author);
        }
        if ($state == 2) {
            $update = $this->setLink($ulink, 'Modifier');
            $delete = $this->setLink($dlink, 'Supprimer');
        }
        $this->legend.='Posté par '.$this->setBalise('strong',$link).' le '.$date.$update.$delete.'</legend>';
         }

    public function setContent($content)
    {
        $this->content .= $content.'</p>';
    }


    public function setBalise($balise,$content)
    {
        return '<'.$balise.'>'.$content.'</'.$balise.'>';
    }
    public function setLink($link,$label)
    {
        return '<a href="'.$link.'">'.$label.'</a>';
    }
    public function Build(array $tab)
    {
        $this->setAttr(array("data-id"=>$tab['id'],"data-news"=>$tab['news']));
        $this->setLegend($tab->link,$tab['auteur'],$tab['date'],$tab['update'],$tab['delete'],$tab['etat']);
        $this->setContent($tab['contenu']);
        return $this->fieldset.$this->legend.$this->content.'</fieldset>';


    }

}