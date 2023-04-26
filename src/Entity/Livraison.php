<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation_livraison = null;

    #[ORM\Column(length: 10)]
    private ?string $etat_livraison = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse_livraison1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse_livraison2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_terminer_livraison = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\ManyToOne(inversedBy: 'livraisons')]
    private ?Utilisateur $id_livreur = null;

    #[ORM\ManyToOne(inversedBy: 'livraisons')]
    private ?Echange $id_echange = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreationLivraison(): ?\DateTimeInterface
    {
        return $this->date_creation_livraison;
    }

    public function setDateCreationLivraison(?\DateTimeInterface $date_creation_livraison): self
    {
        $this->date_creation_livraison = $date_creation_livraison;

        return $this;
    }

    public function getEtatLivraison(): ?string
    {
        return $this->etat_livraison;
    }

    public function setEtatLivraison(string $etat_livraison): self
    {
        $this->etat_livraison = $etat_livraison;

        return $this;
    }

    public function getAdresseLivraison1(): ?string
    {
        return $this->adresse_livraison1;
    }

    public function setAdresseLivraison1(?string $adresse_livraison1): self
    {
        $this->adresse_livraison1 = $adresse_livraison1;

        return $this;
    }

    public function getAdresseLivraison2(): ?string
    {
        return $this->adresse_livraison2;
    }

    public function setAdresseLivraison2(?string $adresse_livraison2): self
    {
        $this->adresse_livraison2 = $adresse_livraison2;

        return $this;
    }

    public function getDateTerminerLivraison(): ?\DateTimeInterface
    {
        return $this->date_terminer_livraison;
    }

    public function setDateTerminerLivraison(?\DateTimeInterface $date_terminer_livraison): self
    {
        $this->date_terminer_livraison = $date_terminer_livraison;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getIdLivreur(): ?Utilisateur
    {
        return $this->id_livreur;
    }

    public function setIdLivreur(?Utilisateur $id_livreur): self
    {
        $this->id_livreur = $id_livreur;

        return $this;
    }

    public function getIdEchange(): ?Echange
    {
        return $this->id_echange;
    }

    public function setIdEchange(?Echange $id_echange): self
    {
        $this->id_echange = $id_echange;

        return $this;
    }
}
