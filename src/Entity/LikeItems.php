<?php

namespace App\Entity;

use App\Repository\LikeItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeItemsRepository::class)]
#[ORM\Table(name: 'like_items')]
class LikeItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
