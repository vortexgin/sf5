<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateTimeValidator extends \Symfony\Component\Validator\Constraints\DateTimeValidator
{

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DateTime) {
            throw new UnexpectedTypeException($constraint, DateTime::class);
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