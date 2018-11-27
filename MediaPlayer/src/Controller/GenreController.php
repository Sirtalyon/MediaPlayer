<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Genre;
use App\Form\GenreType;

class GenreController extends Controller
{
    /**
     * @Route("/genre/list", name="genre_list")
     */
    public function list(EntityManagerInterface $em)
    {

        $genres = $em->getRepository(Genre::class)->findAll();

        return $this->render('genre/list.html.twig', [
            'genres' => $genres,
        ]);
    }


    /**
     * @Route("/genre/add", name="genre_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $genre = new Genre();

        $form = $this->createForm(GenreType::class,$genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre sauvegardé!');
            return $this->redirectToRoute('genre_list');
        }

        return $this->render('genre/add.html.twig', [
            'genreForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/update/{id}", name="genre_update", requirements={"id":"\d+"})
     */
    public function update(Genre $genre, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(GenreType::class,$genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre mis à jour!');
            return $this->redirectToRoute('genre_list');
        }


        return $this->render('genre/update.html.twig', [
            'genreForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/genre/del", name="genre_del_default", defaults={"id":0})
     * @Route("/genre/del/{id}", name="genre_del")
     */
    public function del(EntityManagerInterface $em,Genre $genre)
    {
        //vérification côté serveur
        if(count($genre->getIdeas()) > 0){
            $this->addFlash('error', "Impossible de supprimer le genre");
            return $this->redirectToRoute('genre_list');
        }

        $em->remove($genre);
        $em->flush();
        $this->addFlash("success", "Genre supprimé!");
        return $this->redirectToRoute("genre_list");
    }
}
