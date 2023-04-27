<?php

namespace App\Entity;

use App\Repository\ViewItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViewItemsRepository::class)]
#[ORM\Table(name: 'view_items')]
class ViewItems
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $itemid = null;

    public function getiditem(): ?int
    {
        return $this->itemid;
    }
    #[ORM\Column]
    private ?int $userid = null;

    public function getiduser(): ?int
    {
        return $this->userid;
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


}
