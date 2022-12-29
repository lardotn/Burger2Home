<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[HasLifecycleCallbacks]
class Burger
{
    #[Groups(['burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $name = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

/*
    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('name')]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $name_en = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('slug')]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $slug_en = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('description')]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description_en = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('name')]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $name_fr = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('slug')]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $slug_fr = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    //#[SerializedName('description')]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description_fr = null;
*/

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    private ?float $basePrice = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    private ?float $promoPrice = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[SerializedName('imgPath')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img_path = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[SerializedName('isActive')]
    #[ORM\Column]
    private ?bool $is_active = null;

    #[Groups(['burgers:read', 'burgerDetail:read', 'ingredientDetail:read', 'categoryDetail:read'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\PositiveOrZero()]
    private ?int $burgerPoint = null;

    #[Groups(['burgers:read', 'burgerDetail:read'])]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'burgers')]
    private Collection $categories;
    
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'burgers')]
    private Collection $ingredients;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
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

/*
    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): self
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function getSlugEn(): ?string
    {
        return $this->slug_en;
    }

    public function setSlugEn(string $slug_en): self
    {
        $this->slug_en = $slug_en;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->description_en;
    }

    public function setDescriptionEn(string $description_en): self
    {
        $this->description_en = $description_en;

        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->name_fr;
    }

    public function setNameFr(string $name_fr): self
    {
        $this->name_fr = $name_fr;

        return $this;
    }

    public function getSlugFr(): ?string
    {
        return $this->slug_fr;
    }

    public function setSlugFr(string $slug_fr): self
    {
        $this->slug_fr = $slug_fr;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->description_fr;
    }

    public function setDescriptionFr(string $description_fr): self
    {
        $this->description_fr = $description_fr;

        return $this;
    }
*/

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getPromoPrice(): ?float
    {
        return $this->promoPrice;
    }

    public function setPromoPrice(float $promoPrice): self
    {
        $this->promoPrice = $promoPrice;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(?string $img_path): self
    {
        $this->img_path = $img_path;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getBurgerPoint(): ?int
    {
        return $this->burgerPoint;
    }

    public function setBurgerPoint(?int $burgerPoint): self
    {
        $this->burgerPoint = $burgerPoint;

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }
}
