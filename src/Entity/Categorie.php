<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie {
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

    #[ORM\ManyToMany(targetEntity: Livre::class, mappedBy: 'categories')]
    private Collection $livres;

    public function __construct() {
        $this->livres = new ArrayCollection();
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
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection {
        return $this->livres;
    }

    public function addLivre(Livre $livre): static {
        if(!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->addCategorie($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static {
        if($this->livres->removeElement($livre)) {
            $livre->removeCategorie($this);
        }

        return $this;
    }
}
