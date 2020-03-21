<?php


namespace App\Helper;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait EntityTrait
{

    /**
     * Validate entity by constraint
     * @param $entity
     * @return bool
     */
    protected function validateEntity($entity)
    {
        $errors = $this->validator->validate($entity, null, 'Default');

        if ($errors->count() > 0) {
            throw new BadRequestHttpException(sprintf('%s: %s',
                    $errors->get(0)->getPropertyPath(),
                    $errors->get(0)->getMessage())
            );
        }

        return true;
    }
}