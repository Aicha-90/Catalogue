<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $prix;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $tva;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_commande;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_max_commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getStockCommande(): ?int
    {
        return $this->stock_commande;
    }

    public function setStockCommande(int $stock_commande): self
    {
        $this->stock_commande = $stock_commande;

        return $this;
    }

    public function getStockMaxCommande(): ?int
    {
        return $this->stock_max_commande;
    }

    public function setStockMaxCommande(int $stock_max_commande): self
    {
        $this->stock_max_commande = $stock_max_commande;

        return $this;
    }
}
