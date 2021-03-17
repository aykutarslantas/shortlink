<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\RouterInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()){

//            $mail = ($_SESSION['_sf2_attributes']['_security.last_username']);
//            $en = $this->getDoctrine()->getManager();
//            $urlRepository = $en->getRepository(User::class);
//            $role = $urlRepository->findOneBy([
//                'email'=>$mail
//            ]);

            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN"){
                return $this->redirectToRoute('admin_default');
            }else if ($this->getUser()->getRoles()[0]=="ROLE_USER"){
                return $this->redirectToRoute('profile');
            }


        }else{
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }


    }

    public function UserInt(UserInterface $user){

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        if ($request->request->get('email')){
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $passwordagain = $request->request->get('passwordagain');
            $role = ["ROLE_USER"];

            if ($password != $passwordagain){
                $error[] = 'Password again is not same password';
            }

            if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $error[] = 'Invalid email format';
            }
        }

        if (!isset($error) and isset($password)){
            $user = new User();
            $encoded = $encoder->encodePassword($user,$password);

            $user->setEmail($email)
                ->setPassword($encoded)
                ->setRoles($role);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            echo '<div class="alert alert-success">User Created</div>';
        }


        if (isset($error)){
            return $this->render('signup/signup.html.twig',['errors'=>$error]);
        }else{
            return $this->render('signup/signup.html.twig',['errors'=>'']);
        }


    }
}
