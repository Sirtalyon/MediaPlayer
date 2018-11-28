<?php

namespace App\Controller;

use App\Entity\Genre;
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
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('media/list.html.twig', [
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
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('genre/list.html.twig', [
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
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('utlisateur/list.html.twig', [
            'controller_name' => 'UtilisateurController',
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }
}
