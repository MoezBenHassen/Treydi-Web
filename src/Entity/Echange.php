<?php

namespace App\Entity;

use App\Repository\EchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EchangeRepository::class)]
class Echange
{

    #[ORM\OneToMany(mappedBy: "id_echange", targetEntity: Item::class)]
    private $items;

    /**
     * @[return Collection|Item[]]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_echange = null;

    #[ORM\ManyToOne(inversedBy: 'echanges')]
    private ?Utilisateur $id_user1 = null;

    #[ORM\ManyToOne(inversedBy: 'echanges')]
    private ?Utilisateur $id_user2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $liv_etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Ce champs ne doit pas être vide")]
    #[Assert\Length(min:3, max:20)]
    private ?string $titre_echange = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\OneToMany(mappedBy: 'id_echange', targetEntity: Livraison::class)]
    private Collection $livraisons;

    #[ORM\OneToMany(mappedBy: 'id_echange', targetEntity: EchangeProposer::class)]
    private Collection $echanges_proposer;

    public function __construct()
    {
        $this->livraisons = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->echanges_proposer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEchange(): ?\DateTimeInterface
    {
        return $this->date_echange;
    }

    public function setDateEchange(?\DateTimeInterface $date_echange): self
    {
        $this->date_echange = $date_echange;

        return $this;
    }

    public function getIdUser1(): ?Utilisateur
    {
        return $this->id_user1;
    }

    public function setIdUser1(?Utilisateur $id_user1): self
    {
        $this->id_user1 = $id_user1;

        return $this;
    }

    public function getIdUser2(): ?Utilisateur
    {
        return $this->id_user2;
    }

    public function setIdUser2(?Utilisateur $id_user2): self
    {
        $this->id_user2 = $id_user2;

        return $this;
    }

    public function getLivEtat(): ?string
    {
        return $this->liv_etat;
    }

    public function setLivEtat(?string $liv_etat): self
    {
        $this->liv_etat = $liv_etat;

        return $this;
    }

    public function getTitreEchange(): ?string
    {
        return $this->titre_echange;
    }

    public function setTitreEchange(?string $titre_echange): self
    {
        $this->titre_echange = $titre_echange;

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
     * @return Collection<int, Livraison>
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons->add($livraison);
            $livraison->setIdEchange($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getIdEchange() === $this) {
                $livraison->setIdEchange(null);
            }
        }

        return $this;
    }

    public function getIdProp(): ?EchangeProposer
    {
        return $this->id_prop;
    }

    public function setIdProp(?EchangeProposer $id_prop): self
    {
        $this->id_prop = $id_prop;

        return $this;
    }

    /**
     * @return Collection<int, EchangeProposer>
     */
    public function getEchangesProposer(): Collection
    {
        return $this->echanges_proposer;
    }

    public function addEchangesProposer(EchangeProposer $echangesProposer): self
    {
        if (!$this->echanges_proposer->contains($echangesProposer)) {
            $this->echanges_proposer->add($echangesProposer);
            $echangesProposer->setIdEchange($this);
        }

        return $this;
    }

    public function removeEchangesProposer(EchangeProposer $echangesProposer): self
    {
        if ($this->echanges_proposer->removeElement($echangesProposer)) {
            // set the owning side to null (unless already changed)
            if ($echangesProposer->getIdEchange() === $this) {
                $echangesProposer->setIdEchange(null);
            }
        }

        return $this;
    }
}
