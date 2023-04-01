<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 16777215, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?CategorieArticle $id_categorie = null;

    #[ORM\ManyToOne]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(nullable: true)]
    private bool $archived = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteur = null;

    #[ORM\Column(nullable: true)]
    private ?float $avg_rating = null;

    #[ORM\OneToMany(mappedBy: 'id_article', targetEntity: ArticleRatings::class)]
    private Collection $articleRatings;

    public function __construct()
    {
        $this->articleRatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(?\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getIdCategorie(): ?CategorieArticle
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?CategorieArticle $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

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

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAvgRating(): ?float
    {
        return $this->avg_rating;
    }

    public function setAvgRating(?float $avg_rating): self
    {
        $this->avg_rating = $avg_rating;

        return $this;
    }

    /**
     * @return Collection<int, ArticleRatings>
     */
    public function getArticleRatings(): Collection
    {
        return $this->articleRatings;
    }

    public function addArticleRating(ArticleRatings $articleRating): self
    {
        if (!$this->articleRatings->contains($articleRating)) {
            $this->articleRatings->add($articleRating);
            $articleRating->setIdArticle($this);
        }

        return $this;
    }

    public function removeArticleRating(ArticleRatings $articleRating): self
    {
        if ($this->articleRatings->removeElement($articleRating)) {
            // set the owning side to null (unless already changed)
            if ($articleRating->getIdArticle() === $this) {
                $articleRating->setIdArticle(null);
            }
        }

        return $this;
    }
}
