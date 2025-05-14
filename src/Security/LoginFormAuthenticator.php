<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials; 
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface  
{
    public function __construct(private UserRepository $userRepository, private readonly RouterInterface $router) { 
    }

    public function supports(Request $request): ?bool
    {

        return $request->getPathInfo() === '/login' && $request->isMethod('POST'); 

        dd('inside supports method'); 

        
        // TODO: Implement supports() method.
    }

    public function authenticate(Request $request): Passport
    {
        dump ('inside authenticate method as supports method is true');  

        $email = $request->request->get('email'); 
        $password = $request->request->get('password'); 

        $csrf = $request->request->get('_csrf_token'); 

        return new Passport(
            new UserBadge($email
            // , function($userIdentifier){

            //     $user = $this->userRepository->findOneBy(['email' => $userIdentifier]); 

            //     if (!$user) {
            //         throw new UserNotFoundException( sprintf('%s User not found.', $userIdentifier)); 
            //     }
                
            //     return $user; 

            // }
        ), 
            new CustomCredentials(function($credentials, $user) {

                return $credentials == '12345';     
                // dd($credentials, $user, 15); 
            }, $password)
            // new PasswordCredentials($password), 
            , 
            [
                new CsrfTokenBadge(
                    'authenticate',
                    $csrf   
                ),
                (new RememberMeBadge())->enable(), 
                // new RememberMeBadge(), 
                // new PasswordUpgradeBadge($password), 
            ]  
        ); 


        // TODO: Implement authenticate() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->router->generate('app_homepage') 
        ); 


        dd ('here in onAuthenticationSuccess method'); 
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {

        dump ($exception); 

        $request->getSession()->set(
            Security::AUTHENTICATION_ERROR, 
            $exception 

        ); 

        dump($request->getSession()); 
        dump('here2'); 

        return new RedirectResponse(
            $this->router->generate('app_login') 
        ); 

        dd('here'); 
        // TODO: Implement onAuthenticationFailure() method.
    }

   public function start(Request $request, ?AuthenticationException $authException = null): Response
   {
       /*
        * If you would like this class to control what happens when an anonymous user accesses a
        * protected page (e.g. redirect to /login), uncomment this method and make this class
        * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntrypointInterface.
        *
        * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
        */
   }

}
