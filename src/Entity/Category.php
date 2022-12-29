<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[HasLifecycleCallbacks]
class Category
{
    #[Groups(['categoryDetail:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['categories:read', 'categoryDetail:read', 'burgers:read', 'burgerDetail:read'])]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2,max: 50)]
    private ?string $name = null;

    #[Groups(['categories:read', 'burgers:read', 'burgerDetail:read', 'categoryDetail:read',])]
    #[ORM\Column(length: 50)]
    private ?string $slug = null;
    
    // #[Groups(['burgers:read', 'burgerDetail:read'])]
    // #[SerializedName('name')]
    // #[ORM\Column(length: 50)]
    // #[Assert\NotBlank()]
    // #[Assert\Length(min: 2,max: 50)]
    // private ?string $name_en = null;

    // #[Groups(['burgers:read', 'burgerDetail:read'])]
    // #[SerializedName('name')]
    // #[ORM\Column(length: 50)]
    // #[Assert\NotBlank()]
    // #[Assert\Length(min: 2,max: 50)]
    // private ?string $name_fr = null;
    
    #[Groups(['categoryDetail:read'])]
    #[ORM\ManyToMany(targetEntity: Burger::class, mappedBy: 'categories')]
    private Collection $burgers;

    public function __construct()
    {
        $this->burgers = new ArrayCollection();
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

    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
            $burger->addCategory($this);
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $burger->removeCategory($this);
        }

        return $this;
    }
}
