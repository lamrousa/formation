<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 04/03/2016
 * Time: 17:50
 */

namespace OCFram;



class NotNullValidator extends Validator
{
    public function isValid($value)
    {
        return $value != '';
    }
}
