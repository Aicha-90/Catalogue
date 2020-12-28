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
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class CatalogueController extends AbstractController
{

    /*
    Question 4: affichage du catalogue
    Question 5: creation d'une nouvelle session pour un nouveau panier
    */
    
    /**
     * @Route("/accueil/catalogue", name="catalogue")
     */
    public function index(ProduitRepository $pr, SessionInterface $session, EMI $em): Response
    {   
        //s'il n'existe pas de session alors j'en cree une et un je cree un nouveau panier
        if(!isset($session)){
            $session = new Session();
            $session->start();
            $panier= new Cart;
            $panier->setCheckedOut(0);
            $em->persist($panier);
            $em->flush();
        } 
        
        $lesProduits=$pr->findAll();

        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $lesProduits]);
    }

    /*
    Question 3: creation des tables Cart et Item puis methode total_ttc
    */

    /**
    * @Route("/catalogue/panier/{id}", name="calcule_total_ttc")
    *
    */ 
    public function total_ttc(int $id,ItemRepository $item){

              
        //Initialisation
        $leTotalTtc=0;

        //je récupère le panier grâce à l'id de item
        $panierAcalculer = $item->find($id);

        //j'accède à l'id du panier
        $idPanier=$panierAcalculer->getCart()->getId();
        
        //je récupère les données du panier dans une liste
        $lesItems=$item->findByCart($idPanier);

        //je récupère le nombre de produit diferent commandé
        $nbProduit=count($lesItems);

        //je fais une boucle sur chaque element du panier pour calculer le total ttc
        for( $i=0;$i<=$nbProduit;$i++){
            
            $prixht=$panierAcalculer->getProduit()->getPrix();
            $saTva=$panierAcalculer->getProduit()->getTva();
            $quantite=$panierAcalculer->getQuantity();

            $leTotalTtc+=($prixht+($prixht*$saTva/100))*$quantite;
            
        }

        return $this->render('catalogue/catalogue.html.twig', [ "totalTtc" => $leTotalTtc, "totalQte" => $leTotalQte]);

    }


}
