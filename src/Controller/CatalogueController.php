<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Repository\CartRepository;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface as EMI;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(): Response
    {
        return $this->render('catalogue/catalogue.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    /*
    Question 3: creation des tables Cart et Item puis methode total_ttc
    */

    /**
    * @Route("/panier/{id}", name="total_ttc")
    *
    */ 
    public function total_ttc(int $id, ItemRepository $item){
       
        $leTotalTtc=0;
        //je récupère le panier grâce à l'id de item
        $panierAcalculer = $item->find($id);

        //j'accède à l'id du panier
        $idPanier=$panierAcalculer->getCart()->getId();
        
        //je récupère les données du panier dans une liste
        $lesItems=$item->findByCart($idPanier);

        //je récupère le nombre de produit diferant commandé
        $nbProduit=count($lesItems);

        //je fais une boucle sur chaque element du panier pour calculer le total ttc
        for( $i=0;$i<=$nbProduit;$i++){
            
            $prixht=$panierAcalculer->getProduit()->getPrix();
            $saTva=$panierAcalculer->getProduit()->getTva();
            $quantite=$panierAcalculer->getQuantity();

            $leTotalTtc+=($prixht+($prixht*$saTva/100))*$quantite;
            
        }

        return $this->render('catalogue/catalogue.html.twig', [ "totalTtc" => $leTotalTtc]);

    }

    /*
    Question 4: affichage du catalogue
    */

    /**
     * @Route("/accueil/catalogue", name="commander_catalogue")
     */
    public function afficheCatalogue(ProduitRepository $pr): Response
    {
        $lesProduits=$pr->findAll();

        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $lesProduits]);
    }
}
