<?php

namespace App\Controller;

use App\Document\Base;
use App\Form\Saveable;
use App\Helper\EntityTrait;
use App\Repository\BaseRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractFOSRestController
{

    use EntityTrait;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var BaseRepository
     */
    protected $repo;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var Base
     */
    public $entity;

    /**
     * BaseController constructor.
     * @param DocumentManager $dm
     * @param ValidatorInterface $validator
     */
    public function __construct(DocumentManager $dm, ValidatorInterface $validator)
    {
        $this->dm = $dm;
        $this->validator = $validator;
        $this->repo = $dm->getRepository($this->entity);
    }

    /**
     * Find entity by id
     * @param $id
     * @return object|null
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    protected function findEntity($id)
    {
        $user = $this->repo->find($id);
        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('%s not found', get_class($this->entity)));
        }

        return $user;
    }

    protected function processingForm(string $form, Request $request, $entity = null, $save = null)
    {
        /** @var Form $form */
        $form = call_user_func_array([$this, 'createForm'], !empty($entity) ? [$form, $entity] : [$form]);

        if (!$request->request->has($form->getName())) {
            throw new BadRequestHttpException("Invalid form submission params, all parameter should wrapped with '{$form->getName()}'");
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FormInterface $formType */
            $formType = $form->getConfig()->getType()->getInnerType();
            if (is_callable($save)) {
                return call_user_func($save, $form->getData());
            } elseif ($formType instanceof Saveable) {
                return $formType->save($form->getData());
            }

            throw new \RuntimeException('There\'s no submission process handler attached');
        }

        /** @var \Symfony\Component\Form\FormError $currentError */
        $currentError = $form->getErrors(true)->current();
        /** @var \Symfony\Component\Validator\ConstraintViolation $cause */
        $cause = $currentError->getCause();

        throw new BadRequestHttpException(!empty($cause->getPropertyPath())
            ? sprintf('%s: %s', $cause->getPropertyPath(), $cause->getMessage())
            : $cause->getMessage()
        );
    }
}