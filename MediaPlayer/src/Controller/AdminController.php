<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\TypeMedia;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('genre/list.html.twig', [
            'genres' => $genres,
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
}
