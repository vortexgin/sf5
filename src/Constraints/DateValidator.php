<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateValidator extends \Symfony\Component\Validator\Constraints\Date
{

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Date) {
            throw new UnexpectedTypeException($constraint, Date::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            return;
        }

        parent::validate($value, $constraint);
    }

}