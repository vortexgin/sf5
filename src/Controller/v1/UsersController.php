<?php

namespace App\Controller\v1;

use App\Controller\BaseController;
use App\Document\User;
use App\Exception\RestException;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends BaseController
{

    public $entity = User::class;

    /**
     * URL [GET] /users/{id}.json
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function getUserAction($id)
    {
        $view = $this->view($this->findEntity($id), 200);
        return $this->handleView($view);
    }

    /**
     * URL [POST] /users.json
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function postUsersAction(Request $request)
    {
        /** @var User $user */
        $user = new User();

        $user = $this->repo->bind($user, $request->request->all(), ['email']);
        $user->setPlainPassword($request->request->get('password', ''));
        $this->validateEntity($user);
        $this->dm->persist($user);
        $this->dm->flush();

        $view = $this->view($user, 201);
        return $this->handleView($view);
    }

    /**
     * URL [PUT] /users/{id}.json
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function putUserAction(Request $request, $id)
    {
        $user = $this->findEntity($id);
        $user = $this->repo->bind($user, $request->request->all(), ['name', 'birthDate', 'education', 'work']);
        $this->validateEntity($user);
        $this->dm->persist($user);
        $this->dm->flush();

        $view = $this->view($user, 202);
        return $this->handleView($view);
    }
}