<?php

namespace App\Controller\Admin;

use App\Entity\Pages;
use App\Entity\Slides;
use App\Entity\Setting;
use App\Entity\Url;
use App\Entity\UrlStats;
use App\Entity\User;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_default")
     */
    public function index(): Response
    {
        // buraya en son eklenen "linkleri" ekleyeyim
        // bi altına da sayfalar, slaytlar, anasayfa, gibi sayfaları kolon olarak gösterelim


        $url = $this->getDoctrine()->getRepository(Url::class);
        $urlRepository = $url->findBy(array(),array(),10);

        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'urls' => $urlRepository,
        ]);
    }


    /**
     * @Route("/admin/{slug}", name="admin_slug")
     */
    public function pages($slug, Request $request): Response
    {
        // yukarıdaki slug' a göre yeni sayfaları çekeceğim

        if (!isset($slug)){
            $slug = '';
        }
        switch ($slug){
            case 'slides':
                $slidesRepository = $this->getDoctrine()->getRepository(Slides::class);
                $Repository = $slidesRepository->findAll();
                return $this->render('admin/pages/index.html.twig', [
                    'action' => $slug,
                    'Repository' => $Repository
                ]);
                break;
            case 'pages':
                $pagesRepository = $this->getDoctrine()->getRepository(Pages::class);
                $Repository = $pagesRepository->findAll();
                $addPage = $request->request->get('addPage');

                if (isset($addPage) and $addPage == '1'){

                     $em = $this->getDoctrine()->getManager();
                     $pages = new Pages();

                     $pages->setTitle($request->request->get('title'))
                         ->setContent($request->request->get('content'));

                    $em->persist($pages);
                    $em->flush();
                    return $this->redirect('/admin/pages');
                }
                    return $this->render('admin/pages/index.html.twig', [
                        'action' => $slug,
                        'Repository' => $Repository
                    ]);


                break;
            case 'stats':
                $statsRepository = $this->getDoctrine()->getRepository(UrlStats::class);
                $Repository = $statsRepository->findAll();
                return $this->render('admin/pages/index.html.twig', [
                    'action' => $slug,
                    'Repository' => $Repository
                ]);
                break;
            case 'setting':
                $SettingRepository = $this->getDoctrine()->getRepository(Setting::class);
                $Repository = $SettingRepository->find(1);
                return $this->render('admin/pages/index.html.twig', [
                    'action' => $slug,
                    'Repository' => $Repository
                ]);
                break;

              default:
                return $this->redirectToRoute('admin_default');
        }
    }

}
