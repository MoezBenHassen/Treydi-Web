<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    /*must be not blank*/
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    /*must be between 5 and 255 characters*/
    #[Assert\Length(min: 5, max: 255, minMessage: 'Le titre doit faire au moins 5 caractères', maxMessage: 'Le titre ne peut pas faire plus de 255 caractères')]
    private ?string $titre = null;

    #[ORM\Column(length: 500, nullable: true)]
    /*must be not blank*/
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    /*must be between 5 and 500 characters*/
    #[Assert\Length(min: 5, max: 500, minMessage: 'La description doit faire au moins 5 caractères', maxMessage: 'La description ne peut pas faire plus de 500 caractères')]
    private ?string $description = null;


    #[ORM\Column(length: 16777215, nullable: true)]
    /*must be not blank*/
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    /*must be between 5 and 16777215 characters*/
    #[Assert\Length(min: 5, max: 16777215, minMessage: 'Le contenu doit faire au moins 5 caractères', maxMessage: 'Le contenu ne peut pas faire plus de 16777215 caractères')]
//    doesn't start with a number
    #[Assert\Regex(pattern: '/^[^0-9]/', message: 'Le contenu ne peut pas commencer par un chiffre')]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    /*not blank*/
    #[Assert\NotBlank(message: 'La date de publication ne peut pas être vide')]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    /*not blank*/
    #[Assert\NotBlank(message: 'Veuillez choisir une catégorie')]
    private ?CategorieArticle $id_categorie = null;


    #[ORM\ManyToOne]
    /*not blank*/
    #[Assert\NotBlank(message: 'Veuillez choisir un utilisateur')]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(nullable: true)]
    private bool $archived = false;

    #[ORM\Column(length: 255, nullable: true)]
    /*must be not blank*/
    #[Assert\NotBlank(message: 'L\'auteur ne peut pas être vide')]
    /*must be between 5 and 255 characters*/
    #[Assert\Length(min: 5, max: 255, minMessage: 'L\'auteur doit faire au moins 5 caractères', maxMessage: 'L\'auteur ne peut pas faire plus de 255 caractères')]
    private ?string $auteur = null;

    #[ORM\Column(nullable: true)]
    private ?float $avg_rating = null;

    #[ORM\OneToMany(mappedBy: 'id_article', targetEntity: ArticleRatings::class)]
    private Collection $articleRatings;

    // property rating is not mapped to the database
    private ?float $rating = null;

    /**
     * @return float|null
     */
    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * @param float|null $rating
     */
    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

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
