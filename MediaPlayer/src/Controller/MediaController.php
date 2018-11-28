<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $medias = $em->getRepository(Media::class)->findAll();

        $isConnect = "Se connecter";
        $cheminConnexion = "login";
        $user = $this->getUser();
        if($user!=null){
            $isConnect = "Se déconnecter";
            $cheminConnexion = "logout";
        }
        return $this->render('media/list.html.twig', [
            'medias' => $medias,
            'connecter' => $isConnect,
            'cheminConnexion' => $cheminConnexion
        ]);

    }


    /**
     * @Route("/media/add", name="media_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $media = new Media();

        $form = $this->createForm(MediaType::class,$media);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media sauvegardé!');
            return $this->redirectToRoute('media_list');
        }

        return $this->render('media/add.html.twig', [
            'mediaForm' => $form->createView()
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
