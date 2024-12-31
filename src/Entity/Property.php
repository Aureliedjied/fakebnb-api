<?php

namespace App\Entity;

use App\Entity\Address;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups (['property'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups (['property'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups (['property'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups (['property'])]
    private ?int $pricePerNight = null;

    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Address $location = null;

    /**
     * @var Collection<int, Amenity>
     */
    #[ORM\ManyToMany(targetEntity: Amenity::class, mappedBy: 'Property')]
    private Collection $amenities;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPricePerNight(): ?int
    {
        return $this->pricePerNight;
    }

    public function setPricePerNight(int $pricePerNight): static
    {
        $this->pricePerNight = $pricePerNight;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(Address $location): static
    {
        // Set the owning side of the relation if necessary
        if ($location->getProperty() !== $this) {
            $location->setProperty($this);
        }

        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Amenity>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->addProperty($this);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): static
    {
        if ($this->amenities->removeElement($amenity)) {
            $amenity->removeProperty($this);
        }

        return $this;
    }
}

