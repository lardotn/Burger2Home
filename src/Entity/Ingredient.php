<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[HasLifecycleCallbacks]
class Ingredient
{
    #[Groups(['ingredientDetail:read', 'allergensDetail:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['ingredients:read','ingredientDetail:read', 'burgerDetail:read', 'allergensDetail:read'])]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $name = null;

    #[Groups(['ingredients:read','ingredientDetail:read', 'burgerDetail:read', 'allergensDetail:read'])]
    #[ORM\Column(length: 50)]
    private ?string $slug = null;
    
    // #[SerializedName('name')]
    // #[ORM\Column(length: 50)]
    // #[Assert\NotBlank()]
    // #[Assert\Length(min: 2,max: 50)]
    // private ?string $name_en = null;

    // #[SerializedName('name')]
    // #[ORM\Column(length: 50)]
    // #[Assert\NotBlank()]
    // #[Assert\Length(min: 2,max: 50)]
    // private ?string $name_fr = null;
    
    #[Groups(['ingredients:read','ingredientDetail:read', 'burgerDetail:read', 'allergensDetail:read'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    private ?float $price = null;

    #[Groups(['ingredients:read','ingredientDetail:read', 'burgerDetail:read', 'allergensDetail:read'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\PositiveOrZero()]
    private ?int $quantity = null;

    #[Groups(['ingredientDetail:read'])]
    #[ORM\ManyToMany(targetEntity: Burger::class, mappedBy: 'ingredients')]
    private Collection $burgers;

    #[Groups(['ingredientDetail:read', 'burgerDetail:read'])]
    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'ingredients')]
    private Collection $allergens;

    public function __construct()
    {
        $this->burgers = new ArrayCollection();
        $this->allergens = new ArrayCollection();;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->slug = (new Slugify())->slugify($this->name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    // public function getNameEn(): ?string
    // {
    //     return $this->name_en;
    // }

    // public function setNameEn(string $name_en): self
    // {
    //     $this->name_en = $name_en;

    //     return $this;
    // }

    // public function getNameFr(): ?string
    // {
    //     return $this->name_fr;
    // }

    // public function setNameFr(string $name_fr): self
    // {
    //     $this->name_fr = $name_fr;

    //     return $this;
    // }
    
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
            $burger->addIngredient($this);
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $burger->removeIngredient($this);
        }

        return $this;
    }

    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addAllergen(Allergen $allergen): self
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens[] = $allergen;
            $allergen->addIngredient($this);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): self
    {
        if (!$this->allergens->contains($allergen)) {
            $allergen->removeIngredient($this);
        }

        return $this;
    }
}
