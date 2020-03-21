<?php

namespace App\Form\Security;

use App\Document\User;
use App\Form\BaseForm;
use App\Helper\SecurityHelper;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginForm extends BaseForm
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'maxlength' => 32
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Password',
                'attr' => [
                    'maxlength' => 12
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
        /** @var UserRepository $userRepo */
        $userRepo = $this->dm->getRepository(User::class);

        /** @var \App\Document\User $user */
        if (empty($user = $userRepo->findByEmailOrMobilePhone($params->getEmail()))) {
            throw new NotFoundHttpException('User not found');
        }

        $passwordParams = SecurityHelper::encodePassword($params->getPassword());
        if ($passwordParams !== $user->getPassword()) {
            throw new BadCredentialsException('Invalid credentials');
        }

        return new JsonResponse([
            'token' => SecurityHelper::buildJwt([
                'uid' => $user->getId()
            ])
        ]);
    }
}