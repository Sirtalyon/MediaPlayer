<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Entity\Utilisateur;
use App\Form\GenreType;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            $genre->setIdTypeMedia($typemedia['id']);
            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre sauvegardé!');


            return $this->redirectToRoute('admin_index');
        }
        return $this->render('genre/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'controller_name' => 'GenreController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }
}
