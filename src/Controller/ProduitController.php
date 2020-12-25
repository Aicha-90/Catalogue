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
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    /*
    Question 1:
    Voici les deux methodes permettant de calculer le montant ttc et le stock restant, cependant je n'arrive pas à les utiliser dans les autres fonctions du controlleur
    */
    public function caluleTtc(int $prixHt, int $tva){

        $prixTtc=$prixHt+($prixHt*$tva/100);
        $ttc=round($prixTtc,2);
        return $ttc;
    }

    public function stockDisp(int $stockDisp, int $stockCmd){

        $stockRestant=$stkDisp-$stockCmd;
        return $stockRestant;

    }

    /*
    Question 2:
    Cette fonction permet bien d'ajouter les produits dans la base de donnee, ca marche !
    la vue correspondante est dans template/produit/index.html.twig
    */

    /**
    * @Route("/accueil/ajouter", name="ajouter_produit")
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

            return $this->redirectToRoute("accueil");

        }
        
        return $this->render('produit/produit.html.twig');
    }


}