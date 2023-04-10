<?php

namespace App\Entity;

use App\Repository\CategorieCouponRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieCouponRepository::class)]
#[ORM\Table(name: 'categorie_coupon')]
class CategorieCoupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ nom de la catégorie ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le nom de la catégorie doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de la catégorie doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9\W]\w+$/',
        message: "Le nom de la catégorie peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $nom_categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le champ description de la catégorie ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'La description de la catégorie doit comporter au moins {{ limit }} caractères.',
        maxMessage: 'La description de la catégorie  doit comporter au plus {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[^0-9\W]\w+$/',
        message: "La description de la catégorie ne peut pas commencer par un chiffre ou un symbole."
    )]
    private ?string $description_categorie = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\OneToMany(mappedBy: 'id_categorie', targetEntity: Coupon::class)]
    private Collection $coupons;

    public function __construct()
    {
        $this->coupons = new ArrayCollection();
    }

    public function __toString(): string
    { return $this->description_categorie;
    }

    public function _toString(): string
    { return $this->nom_categorie;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom_Categorie(): ?string
    {
        return $this->nom_categorie;
    }
    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNom_Categorie(?string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    public function setNomCategorie(?string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }
    public function getDescriptionCategorie(): ?string
    {
        return $this->description_categorie;
    }

    public function getDescription_Categorie(): ?string
    {
        return $this->description_categorie;
    }

    public function setDescription_Categorie(?string $description_categorie): self
    {
        $this->description_categorie = $description_categorie;

        return $this;
    }

    public function setDescriptionCategorie(?string $description_categorie): self
    {
        $this->description_categorie = $description_categorie;

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
     * @return Collection<int, Coupon>
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }

    public function addCoupon(Coupon $coupon): self
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons->add($coupon);
            $coupon->setIdCategorie($this);
        }

        return $this;
    }

    public function removeCoupon(Coupon $coupon): self
    {
        if ($this->coupons->removeElement($coupon)) {
            // set the owning side to null (unless already changed)
            if ($coupon->getIdCategorie() === $this) {
                $coupon->setIdCategorie(null);
            }
        }

        return $this;
    }
}
