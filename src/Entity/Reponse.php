<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]

    #[Assert\NotBlank(message: 'Le champ titre de la réponse ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le titre de la réponse doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le titre de la réponse doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9]/',
        message: "Le titre de la réclamation ne peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $titre_reponse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ titre de la réponse ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'La description de la réponse doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La description de la réponse doit comporter au plus {{ limit }} caractères.'
    )]
    private ?string $description_reponse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_reponse = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reclamation $id_reclamation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreReponse(): ?string
    {
        return $this->titre_reponse;
    }

    public function setTitreReponse(?string $titre_reponse): self
    {
        $this->titre_reponse = $titre_reponse;

        return $this;
    }

    public function getDescriptionReponse(): ?string
    {
        return $this->description_reponse;
    }

    public function setDescriptionReponse(?string $description_reponse): self
    {
        $this->description_reponse = $description_reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(?\DateTimeInterface $date_reponse): self
    {
        $this->date_reponse = $date_reponse;

        return $this;
    }

    public function getIdReclamation(): ?Reclamation
    {
        return $this->id_reclamation;
    }

    public function setIdReclamation(?Reclamation $id_reclamation): self
    {
        $this->id_reclamation = $id_reclamation;

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
