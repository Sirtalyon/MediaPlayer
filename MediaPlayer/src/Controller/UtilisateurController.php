<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends Controller
{
    /**
     * @Route("/utilisateur/list", name="utilisateur_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('utilisateur/list.html.twig', [
            'utilisateurs' => $utilisateurs,
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }


    /**
     * @Route("/utilisateur/add", name="utilisateur_add")
     */
    public function add(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $user = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $user);
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
     * @Route("/utilisateur/update/{id}", name="utilisateur_update", requirements={"id":"\d+"})
     */
    public function update(UtilisateurType $utilisateur, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(UtilisateurType::class,$utilisateur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash('success', 'Utilisateur mis à jour!');
            return $this->redirectToRoute('utilisateur_list');
        }


        return $this->render('utilisateur/update.html.twig', [
            'utilisateurForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/utilisateur/del", name="utilisateur_del_default", defaults={"id":0})
     * @Route("/utilisateur/del/{id}", name="utilisateur_del")
     */
    public function del(EntityManagerInterface $em,Utilisateur $utilisateur)
    {
        //vérification côté serveur
        if(count($utilisateur->getIdeas()) > 0){
            $this->addFlash('error', "Impossible de supprimer le Utilisateur");
            return $this->redirectToRoute('utilisateur_list');
        }

        $em->remove($utilisateur);
        $em->flush();
        $this->addFlash("success", "Utilisateur supprimé!");
        return $this->redirectToRoute("utilisateur_list");
    }
}
