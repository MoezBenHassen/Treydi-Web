<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Util\Exception;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\NotBlank(message: 'Libelle est obligatoire! (entre 3 et 30)')]
    #[Assert\Regex(
        pattern: '/^(?!\d+$)\s*[a-zA-Z0-9\s]+$/i',
        message: 'Le libellé doit contenir des lettres et des chiffres, mais ne doit pas être composé uniquement de chiffres.'
    )]
    #[Assert\Length(min:3,max: 30)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Description est obligatoire! (entre 3 et 255)')]
    #[Assert\Length(min:3,max: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $type = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/\.(png|jpg)$/', message: 'image doit etre png ou jpg')]
    private ?string $imageurl = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(nullable: true)]
    private ?int $likes = 0;

    #[ORM\Column(nullable: true)]
    private ?int $dislikes = 0;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = false;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?CategorieItems $id_categorie = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Echange $id_echange = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): self
    {
        $this->imageurl = $imageurl;

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

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(?int $dislikes): self
    {
        $this->dislikes = $dislikes;

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

    public function getIdCategorie(): ?CategorieItems
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?CategorieItems $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

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
