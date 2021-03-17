<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreatedLinksController extends AbstractController
{
    /**
     * @Route("/user/created/links", name="user_created_links")
     */
    public function index(): Response
    {
        return $this->render('user/created_links/index.html.twig', [
            'controller_name' => 'CreatedLinksController',
        ]);
    }
}
