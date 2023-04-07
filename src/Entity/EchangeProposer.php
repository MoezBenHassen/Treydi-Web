<?php

namespace App\Entity;

use App\Repository\EchangeProposerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EchangeProposerRepository::class)]
class EchangeProposer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'echanges_proposer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Echange $id_echange = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_proposer = null;

    #[ORM\ManyToOne(inversedBy: 'echanges_prposer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateProposer(): ?\DateTimeInterface
    {
        return $this->date_proposer;
    }

    public function setDateProposer(?\DateTimeInterface $date_proposer): self
    {
        $this->date_proposer = $date_proposer;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): self
    {
        $this->id_user = $id_user;

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
}
