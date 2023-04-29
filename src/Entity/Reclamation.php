<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ titre de la réclamation ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le titre de la réclamation doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le titre de la réclamation doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9]/',
        message: "Le titre de la réclamation ne peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $titre_reclamation = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ description de la réclamation ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 500,
        minMessage: 'La description de la réclamation doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La description de la réclamation doit comporter au plus {{ limit }} caractères.'
    )]
    private ?string $description_reclamation = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $etat_reclamation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_cloture = null;



    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    private ?string $userFullName = null;
    private ?string $avatarUrl = null;

    /**
     * @return string|null
     */
    public function getUserFullName()
    {
        return $this->userFullName;
    }

    /**
     * @param string|null $userFullName
     */
    public function setUserFullName($userFullName)
    {
        $this->userFullName = $userFullName;
    }

    /**
     * @return string|null
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @param string|null $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreReclamation(): ?string
    {
        return $this->titre_reclamation;
    }

    public function setTitreReclamation(?string $titre_reclamation): self
    {
        $this->titre_reclamation = $titre_reclamation;

        return $this;
    }

    public function getDescriptionReclamation(): ?string
    {
        return $this->description_reclamation;
    }

    public function setDescriptionReclamation(?string $description_reclamation): self
    {
        $this->description_reclamation = $description_reclamation;

        return $this;
    }

    public function getEtatReclamation(): ?string
    {
        return $this->etat_reclamation;
    }

    public function setEtatReclamation(?string $etat_reclamation): self
    {
        $this->etat_reclamation = $etat_reclamation;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->date_cloture;
    }

    public function setDateCloture(?\DateTimeInterface $date_cloture): self
    {
        $this->date_cloture = $date_cloture;

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
