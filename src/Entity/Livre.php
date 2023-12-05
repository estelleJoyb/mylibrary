<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le titre doit faire moins de {{ limit }} caractères.'
    )]
    #[Assert\NotBlank(
        message: 'Le titre ne peut pas être vide'
    )]
    private ?string $titre = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    #[Assert\NotBlank(
        message: 'La catégorie ne peut pas être vide (sinon la bibliothèque n\'est pas rangée!!)'
    )]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    #[Assert\NotBlank(
        message: 'L\'auteur ne peut pas être vide'
    )]
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