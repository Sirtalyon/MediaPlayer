<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeMedia;
use App\Form\TypeMediaType;

class TypeMediaController extends Controller
{
    /**
     * @Route("/typemedia/list", name="type_media_list")
     */
    public function list(EntityManagerInterface $em)
    {

        $typemedias = $em->getRepository(TypeMedia::class)->findAll();

        return $this->render('type_media/list.html.twig', [
            'medias' => $typemedias,
        ]);
    }


    /**
     * @Route("/typemedia/add", name="type_media_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $typemedia = new TypeMedia();

        $form = $this->createForm(TypeMediaType::class,$typemedia);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($typemedia);
            $em->flush();

            $this->addFlash('success', 'TypeMedia sauvegardé!');
            return $this->redirectToRoute('type_media_list');
        }

        return $this->render('type_media/add.html.twig', [
            'typemediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/typemedia/update/{id}", name="type_media_update", requirements={"id":"\d+"})
     */
    public function update(TypeMedia $typemedia, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(TypeMediaType::class,$typemedia);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($typemedia);
            $em->flush();

            $this->addFlash('success', 'TypeMedia mis à jour!');
            return $this->redirectToRoute('type_media_list');
        }


        return $this->render('type_media/update.html.twig', [
            'typemediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/typemedia/del", name="type_media_del_default", defaults={"id":0})
     * @Route("/typemedia/del/{id}", name="type_media_del")
     */
    public function del(EntityManagerInterface $em,TypeMedia $typemedia)
    {
        //vérification côté serveur
        if(count($typemedia->getIdeas()) > 0){
            $this->addFlash('error', "Impossible de supprimer le Typemedia");
            return $this->redirectToRoute('type_media_list');
        }

        $em->remove($typemedia);
        $em->flush();
        $this->addFlash("success", "TypeMedia supprimé!");
        return $this->redirectToRoute("type_media_list");
    }
}
