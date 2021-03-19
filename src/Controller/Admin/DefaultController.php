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
                $addSTextlides = $request->request->get('addSlides');
                $delSlides = $request->request->get('delSlides');
                $upSlides = $request->request->get('upSlides');
                if (!empty($addSTextlides) and $addSTextlides == '1'){
                    $em = $this->getDoctrine()->getManager();
                    $slides = new Slides();
                    $slides->setText($request->request->get('text'));
                    $em->persist($slides);
                    $em->flush();
                    return $this->redirect('/admin/slides');
                }elseif (!empty($delSlides)){
                    $em = $this->getDoctrine()->getManager();
                    $del = $em->getRepository('App:Slides')->find($delSlides);
                    $em->remove($del);
                    $em->flush();
                    return $this->redirect('/admin/slides');
                }elseif (!empty($upSlides)){
                    $text = $request->request->get('text');

                    $em = $this->getDoctrine()->getManager();
                    $up = $em->getRepository('App:Slides')->find($upSlides);
                    $up->setText($text);
                    $em->flush();
                    return $this->redirect('/admin/slides');
                }


                return $this->render('admin/pages/index.html.twig', [
                    'action' => $slug,
                    'Repository' => $Repository
                ]);

                break;

            case 'members':
                // update processes
                $email = $request->request->get("email");
                $role = $request->request->get("role");
                $isactive = $request->request->get("isactive");
                $update = $request->request->get("update");
                $delete = $request->request->get("deletemember");

                if ($isactive == 'on'){
                    $isactive = 1;
                }else{
                    $isactive = 0;
                }

                if ($role == 'admin'){
                    $roles = ["ROLE_ADMIN"];
                }else{
                    $roles = ["ROLE_USER"];
                }
                if ($request and filter_var($email,FILTER_VALIDATE_EMAIL)){


                    if (!empty($update) and is_numeric($update)){
                        $em = $this->getDoctrine()->getManager();
                        $up = $em->getRepository('App:User')->find($update);
                        $up->setEmail($email)
                            ->setRoles($roles)
                            ->setIsActive($isactive);
                        $em->flush();

                    }
                }

                if (!empty($delete) and is_numeric($delete)){
                    $em = $this->getDoctrine()->getManager();
                    $del = $em->getRepository('App:User')->find($delete);
                    $em->remove($del);
                    $em->flush();
                    return $this->redirect('/admin/members');
                }

                $membersRepository = $this->getDoctrine()->getRepository(User::class);
                $Repository = $membersRepository->findAll();
                return $this->render('admin/pages/index.html.twig', [
                    'action' => $slug,
                    'Repository' => $Repository
                ]);
                break;
            case 'pages':
                $pagesRepository = $this->getDoctrine()->getRepository(Pages::class);
                $Repository = $pagesRepository->findAll();
                $addPage = $request->request->get('addPage');
                $delPage = $request->request->get('delete');
                $upPage = $request->request->get('updatePage');

                if (!empty($addPage) and $addPage == '1'){

                    $em = $this->getDoctrine()->getManager();
                    $pages = new Pages();
                    $pages->setTitle($request->request->get('title'))->setContent($request->request->get('content'));
                    $em->persist($pages);
                    $em->flush();
                    return $this->redirect('/admin/pages');
                }elseif (!empty($delPage)){
                    $em = $this->getDoctrine()->getManager();
                    $del = $em->getRepository('App:Pages')->find($delPage);
                    $em->remove($del);
                    $em->flush();
                    return $this->redirect('/admin/pages');
                }elseif (!empty($upPage)){
                    $title = $request->request->get('title');
                    $content = $request->request->get('content');

                    $em = $this->getDoctrine()->getManager();
                    $up = $em->getRepository('App:Pages')->find($upPage);

                    $up->setTitle($title);
                    $up->setContent($content);

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

            case 'urls':
                $urlid = $request->request->get("Urlid");
                $ramdomtext = $request->request->get("ramdomtext");
                $targeturl = $request->request->get("targeturl");

                dump($urlid);
                dump($ramdomtext);
                dump($targeturl);
                if (!empty($urlid) and is_numeric($urlid) ){

                    $em = $this->getDoctrine()->getManager();
                    $up = $em->getRepository('App:Url')->find($urlid);
                    $up->setUrlHash($ramdomtext)
                        ->setUrl($targeturl);
                    $em->flush();
                }

                $UrlRepository = $this->getDoctrine()->getRepository(Url::class);
                $Repository = $UrlRepository->findAll();

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
