<?php

namespace App\Controller;

use App\Document\User;
use App\Form\Security\LoginForm;
use App\Form\Security\RegisterForm;
use App\Model\Security\Login;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{

    public $entity = User::class;

    /**
     * URL [POSt] /securities/logins.json
     * @param Request $request
     * @return mixed
     */
    public function postSecurityLoginAction(Request $request)
    {
        return $this->processingForm(LoginForm::class, $request, new Login());
    }

    public function postSecurityRegisterAction(Request $request)
    {
        return $this->processingForm(RegisterForm::class, $request, new $this->entity());
    }
}