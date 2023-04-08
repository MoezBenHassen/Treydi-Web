<?php

namespace App\Entity;

use App\Repository\CategorieItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Util\Exception;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieItemsRepository::class)]
#[ORM\Table(name: 'categorie_items')]
class CategorieItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\NotBlank(message: 'nom categorie obligatoire! (entre 3 et 30)')]
    #[Assert\Length(min:3,max: 30)]
    private ?string $nom_categorie = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\OneToMany(mappedBy: 'id_categorie', targetEntity: Item::class)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(?string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

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
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setIdCategorie($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getIdCategorie() === $this) {
                $item->setIdCategorie(null);
            }
        }

        return $this;
    }
}
