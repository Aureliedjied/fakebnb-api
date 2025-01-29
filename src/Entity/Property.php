<?php

namespace App\Entity;

use App\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PropertyRepository;
use App\Validator\BanWord;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['property'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 5)]
    #[Groups(['property'])]
    #[BanWord()]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Length(min: 5)]
    #[Assert\Regex('/^[a-z0-9-]+(?:-[a-z0-9-]*)$/', message: 'slug invalid')]
    private ?string $slug = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide.')]
    #[Assert\Length(min: 15)]
    #[BanWord()]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive(message: 'Le prix par nuit doit être un nombre positif.')]
    #[Groups(['property'])]
    private ?int $pricePerNight = null;

    #[ORM\OneToOne(targetEntity: Location::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['property'])]
    private ?Location $location = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive(message: 'Le nombre de chambres doit être un nombre positif.')]
    #[Groups(['property'])]
    private ?int $bedrooms = null;

    #[ORM\ManyToMany(targetEntity: Amenity::class, cascade: ["persist"])] 
    #[Groups(['property'])] 
    private Collection $amenities; 

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['property'])]
    private ?Category $category = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['property'])]
    private ?bool $breakfastIncluded = null;

    public function __construct() 
    { 
        $this->amenities = new ArrayCollection(); 
    }

    // Getters et setters pour les propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPricePerNight(): ?int
    {
        return $this->pricePerNight;
    }

    public function setPricePerNight(int $pricePerNight): self
    {
        $this->pricePerNight = $pricePerNight;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;
        return $this;
    }

    public function getAmenities(): Collection 
    { 
        return $this->amenities; 
    } 

    public function addAmenity(Amenity $amenity): self
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
        }
        return $this;
    }

    public function removeAmenity(Amenity $amenity): self
    {
        $this->amenities->removeElement($amenity);
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function isBreakfastIncluded(): ?bool
    {
        return $this->breakfastIncluded;
    }

    public function setBreakfastIncluded(bool $breakfastIncluded): self
    {
        $this->breakfastIncluded = $breakfastIncluded;
        return $this;
    }
}

