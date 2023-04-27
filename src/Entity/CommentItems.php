<?php

namespace App\Entity;

use App\Repository\CommentItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentItemsRepository::class)]
#[ORM\Table(name: 'comment_items')]
class CommentItems
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $itemid = null;


    #[ORM\Column(length: 300)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $userid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemid(): ?int
    {
        return $this->itemid;
    }


    public function setItemid(int $itemid): self
    {
        $this->itemid = $itemid;

        return $this;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUserid(): ?Utilisateur
    {
        return $this->userid;
    }

    public function setUserid(?Utilisateur $userid): self
    {
        $this->userid = $userid;

        return $this;
    }
}
