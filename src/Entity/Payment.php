<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Hebergement $relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelation(): ?Hebergement
    {
        return $this->relation;
    }

    public function setRelation(?Hebergement $relation): static
    {
        $this->relation = $relation;

        return $this;
    }
}
