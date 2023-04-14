<?php

namespace App\Entity;

use App\Repository\CategorieArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieArticleRepository::class)]
class CategorieArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    /*not blank*/
    #[Assert\NotBlank(message: 'Le libellé ne peut pas être vide')]
    /*must be between 2 and 255 characters*/
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le libellé doit faire au moins 2 caractères', maxMessage: 'Le libellé ne peut pas faire plus de 255 caractères')]
    private ?string $libelle_cat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\OneToMany(mappedBy: 'id_categorie', targetEntity: Article::class)]
    private Collection $articles;

    /*LOCAL ATTRIBUTE THAT DOESN4T GO TO THE DATABASE FOR COUNT*/
    private int $count = 0;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleCat(): ?string
    {
        return $this->libelle_cat;
    }

    public function setLibelleCat(?string $libelle_cat): self
    {
        $this->libelle_cat = $libelle_cat;

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
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setIdCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getIdCategorie() === $this) {
                $article->setIdCategorie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle_cat;
    }

}
