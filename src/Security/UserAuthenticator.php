<?php

namespace App\Security;

use App\Helper\SecurityHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

class UserAuthenticator extends AbstractGuardAuthenticator implements AuthenticatorInterface
{

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new AuthenticationException('Authentication Required');
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        try {
            $token = $request->headers->get('X-AUTH-TOKEN');
            $claims = SecurityHelper::parseJwt((string) $token);

            return (string) $claims['uid'];
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid credential format');
        }
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            // The token header was empty, authentication fails with 401
            throw new AuthenticationException('Please specify the credential');
        }

        return $userProvider->loadUserByUsername($credentials);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AuthenticationException(strtr($exception->getMessageKey(), $exception->getMessageData()));
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}