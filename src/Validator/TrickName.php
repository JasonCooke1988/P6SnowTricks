<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class TrickName
 * @package App\Validator
 * @Annotation
 */
class TrickName extends Constraint
{

    public $message = "Une figure portant ce nom existe déjà.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}