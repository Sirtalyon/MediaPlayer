<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Media;
use App\Form\MediaType;
class MediaController extends Controller
{
    /**
     * @Route("/media/list", name="media_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null) {
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }

        $userId = $user->getId();
        $medias = $em->getRepository(Media::class)->findBy(array('utilisateur'=> $userId));
        $genres = $em->getRepository(Genre::class)->findAll();

        return $this->render('main/mediaUser.html.twig', [
            'medias' => $medias,
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion,
            'genres' => $genres
        ]);
    }


    /**
     * @Route("/media/add", name="media_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
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
            $file->move('C:\wamp64\www\Repository\MediaPlayer\public\media\FileUpload', $name);
            $filePic->move('C:\wamp64\www\Repository\MediaPlayer\public\media\PicUpload', $namePic);
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
            return $this->redirectToRoute('media_list');
        }
        return $this->render('main/add.html.twig', [
            'formAdd' => $formAdd->createView(),
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);
    }

    /**
     * @Route("/media/update/{id}", name="media_update", requirements={"id":"\d+"})
     */
    public function update(Media $media, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(MediaType::class,$media);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media mis à jour!');
            return $this->redirectToRoute('media_list');
        }


        return $this->render('media/update.html.twig', [
            'mediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/media/del", name="media_del_default", defaults={"id":0})
     * @Route("/media/del/{id}", name="media_del")
     */
    public function del(EntityManagerInterface $em,Media $media)
    {
        //vérification côté serveur
        if(count($media->getIdeas()) > 0){
            $this->addFlash('error', "Impossible de supprimer le media");
            return $this->redirectToRoute('media_list');
        }

        $em->remove($media);
        $em->flush();
        $this->addFlash("success", "Media supprimé!");
        return $this->redirectToRoute("media_list");
    }
}
