<?php

namespace App\Entity;

use App\Repository\LikeItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeItemsRepository::class)]
#[ORM\Table(name: 'like_items')]
class LikeItems
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $itemid = null;

    public function getiditem(): ?int
    {
        return $this->itemid;
    }
    #[ORM\Id]
    #[ORM\Column]
    private ?int $userid = null;

    public function getiduser(): ?int
    {
        return $this->userid;
    }
    #[ORM\Column]
    private ?int $liked = null;

    public function getlike(): ?int
    {
        return $this->liked;
    }

    public function setiduser(?int $iduser): self
    {
        $this->userid = $iduser;

        return $this;
    }

    public function setiditem(?int $iditem): self
    {
        $this->itemid = $iditem;

        return $this;
    }

    public function setlike(?int $liked): self
    {
        $this->liked = $liked;

        return $this;
    }


}