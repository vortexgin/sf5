<?php

namespace App\Form\Security;

use App\Document\User;
use App\Form\BaseForm;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class RegisterForm extends BaseForm
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
                'attr' => [
                    'maxlength' => 32
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'maxlength' => 32
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'maxlength' => 12,
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'maxlength' => 12,
                    ]
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function save($params)
    {
        try {
            /** @var User $params */
            $params->setPlainPassword($params->getPassword())
                ->setIsActive(false);
            $this->validateEntity($params);
            $this->dm->persist($params);
            $this->dm->flush();

            return new JsonResponse($this->serializer->normalize($params, 'json', [AbstractNormalizer::ATTRIBUTES => User::$fields]), 201);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}