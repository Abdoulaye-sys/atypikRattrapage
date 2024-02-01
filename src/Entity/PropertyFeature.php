<?php

namespace App\Entity;

use App\Repository\PropertyFeatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyFeatureRepository::class)]
class PropertyFeature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'propertyFeatures')]
    private ?property $property = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?property
    {
        return $this->property;
    }

    public function setProperty(?property $property): static
    {
        $this->property = $property;

        return $this;
    }
}
