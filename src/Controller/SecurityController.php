<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {

        dump ( $request->getSession() ); 

        // if (  $d = $authenticationUtils->getLastAuthenticationError() ) { 
        //     dd (

        //         $authenticationUtils->getLastAuthenticationError(), 
        //         $d 
        //     ); 
        // } 

        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError() 
        ]); 
    }
}
