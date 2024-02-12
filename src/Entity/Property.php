<?php
// src/Entity/Property.php
namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pcontent = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $bedroom = null;

    #[ORM\Column]
    private ?int $bathroom = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pimage = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: PropertyFeature::class, cascade: ["persist", "remove"])]
    private Collection $propertyFeatures;

    public function __construct()
    {
        $this->propertyFeatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPcontent(): ?string
    {
        return $this->pcontent;
    }

    public function setPcontent(string $pcontent): static
    {
        $this->pcontent = $pcontent;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBedroom(): ?int
    {
        return $this->bedroom;
    }

    public function setBedroom(int $bedroom): static
    {
        $this->bedroom = $bedroom;

        return $this;
    }

    public function getBathroom(): ?int
    {
        return $this->bathroom;
    }

    public function setBathroom(int $bathroom): static
    {
        $this->bathroom = $bathroom;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPimage(): ?string
    {
        return $this->pimage;
    }

    public function setPimage(string $pimage): static
    {
        $this->pimage = $pimage;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, PropertyFeature>
     */
    public function getPropertyFeatures(): Collection
    {
        return $this->propertyFeatures;
    }

    public function addPropertyFeature(PropertyFeature $propertyFeature): static
    {
        if (!$this->propertyFeatures->contains($propertyFeature)) {
            $this->propertyFeatures->add($propertyFeature);
            $propertyFeature->setProperty($this);
        }

        return $this;
    }

    public function removePropertyFeature(PropertyFeature $propertyFeature): static
    {
        if ($this->propertyFeatures->removeElement($propertyFeature)) {
            // set the owning side to null (unless already changed)
            if ($propertyFeature->getProperty() === $this) {
                $propertyFeature->setProperty(null);
            }
        }

        return $this;
    }
}