<?php

namespace App\Controller;

use App\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/pages/{slug}", name="pages")
     */
    public function index($slug): Response
    {
        $em = $this->getDoctrine()->getRepository(Pages::class);
        $page = $em->find($slug);

        return $this->render('pages/index.html.twig', [
            'page' => $page,
        ]);
    }
}
