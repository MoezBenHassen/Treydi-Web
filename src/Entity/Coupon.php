<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ titre du coupon ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le titre du coupon doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le titre du coupon doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9\W]\w+$/',
        message: "Le titre du coupon ne peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $titre_coupon = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ description du coupon ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le description du coupon doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le description du coupon doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9\W]\w+$/',
        message: "Le description du coupon peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $description_coupon = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(
        value: 'today',
        message: 'La date doit être supérieure à la date d\'aujourd\'hui.'
    )]
    private ?\DateTimeInterface $date_expiration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat_coupon = null;

    #[ORM\ManyToOne(inversedBy: 'coupons')]
    private ?Utilisateur $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'coupons')]
    private ?CategorieCoupon $id_categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreCoupon(): ?string
    {
        return $this->titre_coupon;
    }

    public function getTitre_Coupon(): ?string
    {
        return $this->titre_coupon;
    }

    public function setTitreCoupon(?string $titre_coupon): self
    {
        $this->titre_coupon = $titre_coupon;

        return $this;
    }

    public function getDescriptionCoupon(): ?string
    {
        return $this->description_coupon;
    }

    public function getDescription_Coupon(): ?string
    {
        return $this->description_coupon;
    }

    public function setDescriptionCoupon(?string $description_coupon): self
    {
        $this->description_coupon = $description_coupon;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function getDate_Expiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }
    public function setDateExpiration(?\DateTimeInterface $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getEtatCoupon(): ?string
    {
        return $this->etat_coupon;
    }
    public function getEtat_Coupon(): ?string
    {
        return $this->etat_coupon;
    }

    public function setEtatCoupon(?string $etat_coupon): self
    {
        $this->etat_coupon = $etat_coupon;

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

    public function getIdCategorie(): ?categorieCoupon
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?categorieCoupon $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

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

    /**
     * @return bool|null
     */
    public function getArchived(): ?bool
    {
        return $this->archived;
    }

}
