<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{


    /**
      * @Route("/api/me", name="api_me")
     */ 
    public function apiMe(): Response
    {
        return $this->json($this->getUser(), 200, [], [
            // 'groups' => ['user:read'] 
        ]); 
    } 

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
