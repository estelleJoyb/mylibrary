<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Auteur $auteur = null;

    public function __construct() {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitre(): ?string {
        return $this->titre;
    }

    public function setTitre(string $titre): static {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection {
        return $this->categories;
    }

    public function addCategorie(Categorie $category): static {
        if(!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategorie(Categorie $category): static {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getAuteur(): ?Auteur {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): static {
        $this->auteur = $auteur;

        return $this;
    }
}