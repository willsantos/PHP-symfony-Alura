<?php

namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuth extends AbstractGuardAuthenticator
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    public function supports(Request $request)
    {
        return $request->getPathInfo() !== '/login';
    }

    public function getCredentials(Request $request)
    {
        $token = str_replace('Bearer ','',$request->headers->get('Authorization'));

        try{
            return JWT::decode($token,'chave',['HS256']);
        }catch (\Exception $exception){
            return false;
        }

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if(!is_object($credentials) || !property_exists($credentials,'username')){
            return null;
        }

        $username = $credentials->username;

        return $this->repository->findOneBy(['username'=>$username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return is_object($credentials) && property_exists($credentials,'username');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['error'=>'Falha na Autenticação'],Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}