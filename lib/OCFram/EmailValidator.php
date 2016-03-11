<?php
/**
 * Created by PhpStorm.
 * User: alamrous
 * Date: 11/03/2016
 * Time: 16:36
 */

namespace OCFram;

use OCFram\Validator;
class EmailValidator extends Validator
{

    public function isValid($value)
    {
        return (filter_var($value, FILTER_VALIDATE_EMAIL));

    }
}