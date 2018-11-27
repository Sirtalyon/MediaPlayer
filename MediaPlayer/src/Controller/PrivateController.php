<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class PrivateController extends Controller
{
    /**
     * @Route("/private", name="private_index")
     */
    public function index()
    {
        return $this->render('private/index.html.twig', [
            'controller_name' => 'PrivateController',
        ]);
    }
}
