<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    public function caluleTtc(int $prixHt, int $tva){

        $prixTtc=$prixHt*$tva/100;
        $ttc=round($prixTtc,2);
        return $ttc;
    }

    public function stockDisp(int $stockDisp, int $stockCmd){

        $stockRestant=$stkDisp-$stockCmd;
        return $stockRestant;
        
    }

}
