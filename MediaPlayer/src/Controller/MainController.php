<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Entity\Utilisateur;
use App\Form\MediaType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends Controller
{
    /**
     * @Route("/", name="main_index")
     */
    public function index( UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        $roles = null;
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
            $roles = $user->getRoles();
            if($roles[0]=="ROLE_ADMIN")
            {
                return $this->redirectToRoute('admin_index', [
                    'controller_name' => 'AdminController',
                    'connecter' => $isConnect,
                    'cheminConnexion' => $cheminConnexion
                ]);
            }
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion,
            'roles' => $roles
        ]);

    }

    /**
     * @Route("/register", name="register")
     */
    public function registration(Request $request,
                                 UserPasswordEncoderInterface $encoder,
                                 EntityManagerInterface $em){

        $user = new Utilisateur();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //crypter le mot de passe
            $pass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pass);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');

        }

        return $this->render('main/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

    /**
     * @Route("/addMedia", name="formMedia")
     */
    public function formMedia(Request $request, EntityManagerInterface $em){

        $media = new Media();

        $formAdd = $this->createForm(MediaType::class, $media);
        $formAdd->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('main/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }

}
