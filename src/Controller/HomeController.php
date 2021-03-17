<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Entity\Slides;
use App\Entity\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $pageRepository = $this->getDoctrine()->getRepository(Pages::class);
        $pages = $pageRepository->findAll();

        $slidesRepository = $this->getDoctrine()->getRepository(Slides::class);
        $slides = $slidesRepository->findAll();

        // TODO: GETS DTB
        return $this->render('home/index.html.twig', [
            'link'=> $pages,
            'slides'=>$slides,
        ]);
    }



}
