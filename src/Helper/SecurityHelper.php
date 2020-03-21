<?php

namespace App\Helper;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class SecurityHelper
{

    /**
     * Function to encoding password
     * @param $string
     * @param null $salt
     * @return string
     */
    public static function encodePassword($string, $salt = null)
    {
        return hash_hmac('sha256', $string, $salt);
    }

    /**
     * Function to generate JWT token
     * @param array $params
     * @return string
     */
    public static function buildJwt(array $params = [])
    {
        if (empty($params)) {
            throw new BadRequestHttpException('Please specify content params');
        }

        $time = time();
        $jwtBuilder = (new Builder())->issuedBy(getenv('JWT_ISSUER'))
            ->permittedFor(getenv('JWT_PERMITTED_FOR'))
            ->identifiedBy(getenv('APP_SECRET'), true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 1)
            ->expiresAt($time + getenv("JWT_EXPIRATION"));

        foreach ($params as $key=>$value) {
            $jwtBuilder->withClaim($key, $value);
        }

        return (string) $jwtBuilder->getToken();
    }

    /**
     * Function to parse and get the data from JWT
     * @param string $token
     * @return array
     */
    public static function parseJwt(string $token)
    {
        /** @var Token $token */
        $token = (new Parser())->parse($token);

        if ($token->getClaim('exp') > time()) {
            throw new BadCredentialsException('Token already expired');
        }

        return $token->getClaims();
    }
}