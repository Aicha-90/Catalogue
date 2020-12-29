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
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class CatalogueController extends AbstractController
{

    /*
    Question 4: affichage du catalogue dans la route "catalogue"
    Question 5: creation d'une nouvelle session pour un nouveau panier dans la route "catalogue_accueil"
    */

    
    /**
     * @Route("/accueil", name="catalogue_accueil")
     */
    public function index(cartRepository $panier, EMI $em, SessionInterface $session) : Response
    { 

        $panier= new Cart;
        $panier->setCheckedOut(false);
        $em->persist($panier);
        $em->flush();

        $id=$panier->getId();

        $panier=$session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }

        $session->set('panier',$panier);
        

        return $this->render('catalogue/index.html.twig',[
            'idPanier' => $id] );
    }

    /**
    * @Route("/accueil/catalogue/{id}/{idProd}/{qte}", name="catalogue")
    */
    public function catalogue(int $id, int $idProd, int $qte, ProduitRepository $pr, CartRepository $cr, EMI $em): Response
    {   
        //Récupération des donnees grâce aux id
        $lesProduits=$pr->findAll();
        $lePanier=$cr->find($id);
        $produit=$pr->find($idProd);
        
        //Creation d'un nouveau item
        $item= new Item;
        $item->setProduit($produit);
        $item->setCart($lePanier);
        $item->setQuantity($qte);

        //Enregistrement en BDD
        $em->persist($item);
        $em->flush();


        //Gestion du stock commandé et max

        if(!empty($produit)){

        $stockDisponible=$produit->getStockMaxCommande();
        $stockcommande=$produit->getStockCommande();

            if( $qte <= $stockDisponible ){

                $nouveauStock=$stockDisponible-$qte;
                $nouveauStockCommande=$stockcommande+$qte;
                $produit->setStockCommande($nouveauStockCommande);
                $produit->setStockMaxCommande($nouveauStock);

                $em->persist($produit);
                $em->flush();
            }
        }

        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $lesProduits, "panier" => $lePanier]);
    }



    /*
    Question 3: creation des tables Cart et Item puis methode total_ttc, ici l'id a renseigner est celle de item
    *

    /* j'ai retirer le code qui était içi car il n'aucune utilité */

    /*
    Question 7: une fois qu'on click sur "Commander" l'action du formulaire qui se trouve dans "catalogue.html.twiq", nous renvoie vers la route ci-dessous "catalogue_commander". Elle permet de modifier le checkedOut du panier puis de retourner vers la page d'accueil "templates/catalogue/index.html.twig"
    */


    /**
    * @Route("/accueil/catalogue/commander/{id}", name="catalogue_commander")
    *
    */ 
    public function commander(int $id, CartRepository $cr, EMI $em){
        
        $panierCheckedOut=$cr->find($id);
        $panierCheckedOut->setCheckedOut(true);
        $em->persist($panierCheckedOut);
        $em->flush();


        return $this->redirectToRoute("catalogue_accueil");
    }


}
