<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Entity\Utilisateur;
use App\Form\GenreType;
use App\Form\MediaType;
use App\Form\TypeMediaType;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    /**
     * @Route("admin/index", name="admin_index")
     */
    public function index()
    {
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/genre", name="genre_index")
     */
    public function genre(EntityManagerInterface $em)
    {
        $genres = $em->getRepository(Genre::class)->findAll();
        $typemedias = $em->getRepository(TypeMedia::class)->findAll();
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('genre/list.html.twig', [
            'genres' => $genres,
            'typemedias' => $typemedias,
            'controller_name' => 'GenreController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/media", name="media_index")
     */
    public function media(EntityManagerInterface $em)
    {
        $medias = $em->getRepository(Media::class)->findAll();
        $genres = $em->getRepository(Genre::class)->findAll();
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('media/list.html.twig', [
            'medias' => $medias,
            'genres' => $genres,
            'utilisateurs' => $utilisateurs,
            'controller_name' => 'MediaController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/type_media", name="typemedia_index")
     */
    public function typeMedia(EntityManagerInterface $em)
    {
        $typemedias = $em->getRepository(TypeMedia::class)->findAll();
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('type_media/list.html.twig', [
            'typemedias' => $typemedias,
            'controller_name' => 'TypeMediaController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/utilisateur", name="utilisateur_index")
     */
    public function utilisateur(EntityManagerInterface $em)
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
            'controller_name' => 'UtilisateurController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/addmedia", name="media_ajouter")
     */
    public function addMedia(Request $request,EntityManagerInterface $em)
    {
        $media = new Media();

        $formAdd = $this->createForm(MediaType::class,$media);

        $formAdd->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }

        if($formAdd->isSubmitted() && $formAdd->isValid()){
            $file = $formAdd->get('name')->getData();
            $filePic = $formAdd->get('picture')->getData();
            $name = $file->getClientOriginalName();
            $namePic = $filePic->getClientOriginalName();
            $mediaRequest = $request->request->get('media');
            $split = explode('.', $name);
            $nameSep = $split[0];
            $extension = $split[1];
            $media->setName($nameSep);
            $media->setDescription($mediaRequest['description']);
            $media->setExtension($extension);
            $media->setPicture($namePic);
            $media->setDateCreated(new \DateTime());
            $media->setUtilisateur($user);
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media sauvegardé!');
            return $this->redirectToRoute('main_index');
        }
        return $this->render('media/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'controller_name' => 'MediaController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/addgenre", name="genre_ajouter")
     */
    public function addGenre(Request $request,EntityManagerInterface $em)
    {
        $genre = new Genre();

        $formAdd = $this->createForm(GenreType::class,$genre);

        $formAdd->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }

        if($formAdd->isSubmitted() && $formAdd->isValid()){
            $genreRequest = $request->request->get('genre');
            $typemedias = $em->getRepository(TypeMedia::class)->findBy(array('id'=> $genreRequest['id_TypeMedia']));
            $genre->setName($genreRequest['name']);
            $genre->setIdTypeMedia($typemedias[0]);
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre sauvegardé!');


            return $this->redirectToRoute('genre_index');
        }
        return $this->render('genre/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'controller_name' => 'GenreController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }


    /**
     * @Route("admin/utilisateur/add", name="utilisateur_admin")
     */
    public function addUtilisateur(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();

        $formAdd = $this->createForm(UtilisateurType::class,$utilisateur);

        $formAdd->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }


        if($formAdd->isSubmitted() && $formAdd->isValid()){
            //crypter le mot de passe
            $pass = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($pass);
            $utilisateur->setRoles(['ROLE_USER']);
            $em->persist($utilisateur);
            $em->flush();


            $this->addFlash('success', 'Utilisateur sauvegardé!');
            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'controller_name' => 'UtilisateurController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("admin/addtypemedia", name="typemedia_ajouter")
     */
    public function addTypeMedia(Request $request,EntityManagerInterface $em)
    {
        $typemedia = new TypeMedia();

        $formAdd = $this->createForm(TypeMediaType::class,$typemedia);

        $formAdd->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }


        if($formAdd->isSubmitted() && $formAdd->isValid()){
            $em->persist($typemedia);
            $em->flush();


            $this->addFlash('success', 'TypeMedia sauvegardé!');
            return $this->redirectToRoute('typemedia_index');
        }

        return $this->render('type_media/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'controller_name' => 'TypeMediaController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("/admin/update/{id}", name="typemedia_update", requirements={"id":"\d+"})
     */
    public function updateTypeMedia(TypeMedia $typemedia, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(TypeMediaType::class,$typemedia);

        $form->handleRequest($request);

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($typemedia);
            $em->flush();

            $this->addFlash('success', 'TypeMedia mis à jour!');
            return $this->redirectToRoute('typemedia_index');
        }


        return $this->render('type_media/update.html.twig', [
            'typemediaForm' => $form->createView(),
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }


    /**
     * @Route("/admin/del", name="typemedia_del_default", defaults={"id":0})
     * @Route("/admin/del/{id}", name="typemedia_del")
     */
    public function del(EntityManagerInterface $em,TypeMedia $typemedia)
    {
        $em->remove($typemedia);
        $em->flush();
        $this->addFlash("success", "Type Media supprimé!");
        return $this->redirectToRoute("typemedia_index");
    }

    /**
     * @Route("/admin/del", name="genre_del_default", defaults={"id":0})
     * @Route("/admin/del/{id}", name="genre_del")
     */
    public function delGenre(EntityManagerInterface $em,Genre $genre)
    {
        $em->remove($genre);
        $em->flush();
        $this->addFlash("success", "Genre supprimé!");
        return $this->redirectToRoute("genre_index");
    }
}
