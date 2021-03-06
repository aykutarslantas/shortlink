<?php

namespace App\Controller;

use App\Entity\Url;
use App\Entity\UrlStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UrlController extends AbstractController
{
    /**
     * @Route("/url", name="url")
     */
    public function index(): Response
    {
        return $this->render('url/index.html.twig', [
            'controller_name' => 'UrlController',
        ]);
    }


    /**
     * @Route("/url/create", name="url_create")
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {

        $resolution = $request->request->get('resolution');

        $url = $request->get('url');
        $constraints = new Assert\Collection([
           'url' => [new Assert\Url()]
        ]);

        $violations = $validator->validate([
            'url'=>$url
        ],$constraints);

        $accesor = PropertyAccess::createPropertyAccessor();
        $errorMessages = [];
        foreach ($violations as $v){
            $accesor->setValue($errorMessages, $v->getPropertyPath(),$v->getMessage());
        }

        $alphanumeric = '0123456789qwertyuopilkjhgfdsazxcvbnm';
        $url_hash = substr(str_shuffle($alphanumeric),0,5);

        $en = $this->getDoctrine()->getManager();


//        if (!empty($update) and is_numeric($update)){
//            $em = $this->getDoctrine()->getManager();
//            $up = $em->getRepository('App:User')->find($update);
//            $up->setEmail($email)
//                ->setRoles($roles)
//                ->setIsActive($isactive);
//            $em->flush();
//
//        }
//
        if ($this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($this->getUser());
            $userid = $user->getId();
            $mail = $user->getEmail();
        }else{
            $userid = 0;
            $mail = null;
        }
        $url_item = new Url();
        $url_item->setUrl($url)
            ->setUrlHash($url_hash)
            ->setUserId($userid)
            ->setClickCount(0)
            ->setIsPublic(0)
            ->setExpiretAt( new \DateTime())
            ->setCreatedAt( new \DateTime())
            ->setIsActive(true)
        ->setUserMail($mail);

        $en->persist($url_item);
        $en->flush();

        return new JsonResponse([
            'success'=>count($errorMessages) === 0 ?? false,
            'response'=>'http://s.s/'.$url_hash,
            'error'=>count($errorMessages) > 0 ?? false,
            'errorMessage'=>count($errorMessages) > 0 ? $errorMessages:null
        ], 200);
    }

    /**
     * @Route("/{urlHash}", name="redirector")
     */
    public function redirector(Request $request, $urlHash): Response
    {

        $en = $this->getDoctrine()->getManager();


        $urlRepository = $en->getRepository(Url::class);
        $url_item = $urlRepository->findOneBy([
            'is_active'=>true,
            'urlHash' => $urlHash
        ]);


        if ($url_item){
            $url = $url_item->getUrl();

            $urlId = $url_item->getId();

            $this->savestas($urlId, $request);

            return $this->redirect($url);
        }

        return $this->redirectToRoute('home');


    }

    public function savestas($urlId, Request $request){

        $userAgent = $request->headers->get('User-Agent');
        $pattern = '/\((.*?)\)/si';

        preg_match_all($pattern,$userAgent,$result);
        $device = ($result[0][0]);

        $userAgent = $request->headers->get('User-Agent');
        $clientIp = $request->getClientIp();
        $locale = $request->getLocale();

        $cityJson = json_decode(file_get_contents("http://ip-api.com/json/$clientIp"));


        if ($cityJson->status=="fail"){
            $city = 'LOCAL SERVER';
        }else{
            $city = $cityJson['regionName'];
        }

        $em = $this->getDoctrine()->getManager();


        $url_stats = new UrlStats();
        $url_stats
            ->setBrowser($userAgent)
            ->setIpAddress($clientIp)
            ->setUrlId($urlId)
            ->setDevice($device)
            ->setResolution('1920x1080')
            ->setLocale($locale)
            ->setCity($city)
            ->setCountry($locale)
            ->setCreatedAt(new \DateTime());

        $em->persist($url_stats);
        $em->flush();
    }
}
