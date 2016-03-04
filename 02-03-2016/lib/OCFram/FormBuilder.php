<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 18:05
 */

namespace OCFram;


abstract class FormBuilder
{
    protected $form;

    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }

    abstract public function build();

    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    public function form()
    {
        return $this->form;
    }

}