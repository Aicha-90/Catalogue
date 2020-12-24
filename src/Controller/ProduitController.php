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

        $prixTtc=$prixHt+($prixHt*$tva/100);
        $ttc=round($prixTtc,2);
        return $ttc;
    }

    public function stockDisp(int $stockDisp, int $stockCmd){

        $stockRestant=$stkDisp-$stockCmd;
        return $stockRestant;

    }

    /**
    * @Route("/catalogue/ajouter", name="ajouter_produit")
    * 
    */
    public function ajouterProduit(Request $request, EMI $em): Response
    {
        
        // Récupération des données envoyées par le formulaire
        if($request->isMethod("POST")){ 
            $nom = $request->request->get('nom');
            $prix = $request->request->get('prix');
            $stockComd = $request->request->get('stockCommande');
            $stockMaxComd = $request->request->get('stockMaxCommande');
            $tva = $request->request->get('tva');

            // Création d'un objet Produit avec les données récupérées
            $produit= new Produit;
            $produit->setNom($nom);
            $produit->setPrix($prix);
            $produit->setStockCommande($stockComd);
            $produit->setStockMaxCommande($stockMaxComd);
            $produit->setTva($tva);
            
            // Enregistrement en BDD
            $em->persist($produit);
            $em->flush();

        }
        return $this->redirectToRoute("catalogue");
    }
    
    /*Faire une méthode total_ttc sur le modèle de panier permettant de calculer le montant total du
panier du client.*/

    /**
    * @Route("/catalogue/panier/{id}", name="total_ttc")
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

        return $this->render('produit/index.html.twig', [ "totalTtc" => $leTotalTtc]);

    }

}