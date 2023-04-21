<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
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


    #[ORM\Column(nullable: true)]
    private ?float $avg_rating = null;

    #[ORM\OneToMany(mappedBy: 'id_article', targetEntity: ArticleRatings::class)]
    private Collection $articleRatings;

    // property rating is not mapped to the database
    private ?float $rating = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'article_image', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Authors $Auteur = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }


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

    public function getAuteur(): ?Authors
    {
        return $this->Auteur;
    }

    public function setAuteur(?Authors $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }
}
