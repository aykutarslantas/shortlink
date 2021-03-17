<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class profileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function index(): Response
    {
        return $this->render('user/profile/index.html.twig', [
            'controller_name' => 'profileController',
        ]);
    }
}
