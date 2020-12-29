<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item")
     */
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    /**
     * @Route("/item/ajouter/", name="item_ajouter")
     */
    public function ajouterItem(): Response
    {       

        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }
}
