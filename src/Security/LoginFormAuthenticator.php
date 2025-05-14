<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials; 
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator   
{

    use TargetPathTrait; 

    public function __construct(private UserRepository $userRepository, private readonly RouterInterface $router) { 
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
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

        if ( $targetPath = $this->getTargetPath($request->getSession(), $firewallName)) { 
            return new RedirectResponse($targetPath); 
        } 


        return new RedirectResponse(
            $this->router->generate('app_homepage') 
        ); 


        dd ('here in onAuthenticationSuccess method'); 
        // TODO: Implement onAuthenticationSuccess() method.
    }


}
