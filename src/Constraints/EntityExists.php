<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

class EntityExists extends Constraint
{
    const NOT_EXISTS_ERROR = 'lrhwb6pv-iabwgn0r-t76fgbru-rn3rj4a3';

    public $message = 'This value is not exists.';
    public $fields = [];
    public $em = null;
    public $entityClass = null;
    public $repositoryMethod = 'findBy';
    public $targetFields = ['id'];
    public $errorPath = null;
    public $ignoreNull = true;

    protected static $errorNames = [
        self::NOT_EXISTS_ERROR => 'NOT_UNIQUE_ERROR',
    ];

    public function getRequiredOptions()
    {
        return ['fields'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getDefaultOption()
    {
        return 'fields';
    }
}