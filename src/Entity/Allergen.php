<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Repository\AllergenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AllergenRepository::class)]
#[HasLifecycleCallbacks]
class Allergen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['burgerDetail:read'])]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $slug = null;

/*
    #[Groups(['burgerDetail:read'])]
    #[SerializedName('name')]
    #[ORM\Column(length: 50)]
    private ?string $name_en = null;

    #[Groups(['burgerDetail:read'])]
    #[SerializedName('name')]
    #[ORM\Column(length: 50)]
    private ?string $name_fr = null;
*/

    #[ORM\ManytoMany(targetEntity: Ingredient::class, mappedBy: 'allergers')]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, mappedBy: 'allergens')]
    private Collection $ingredients;

    public function __construct()
    {
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
        $this->name_en = $name;

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

    public function getNameFr(): ?string
    {
        return $this->name_fr;
    }

    public function setNameFr(string $name_fr): self
    {
        $this->name_fr = $name_fr;

        return $this;
    }
*/

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getIngredient(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addAllergen($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $ingredient->removeAllergen($this);
        }

        return $this;
    }
}
