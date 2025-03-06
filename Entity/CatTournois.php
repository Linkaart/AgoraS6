<?php

namespace App\Entity;

use App\Repository\CatTournoisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatTournoisRepository::class)]
class CatTournois
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Tournois>
     */
    #[ORM\OneToMany(targetEntity: Tournois::class, mappedBy: 'categorie')]
    private Collection $tournois;

    public function __construct() {
        $this->tournois = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getLibelle(): ?string {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Collection<int, Tournois>
     */
    public function getTournois(): Collection {
        return $this->tournois;
    }

    public function addTournois(Tournois $tournois): static {
        if (!$this->tournois->contains($tournois)) {
            $this->tournois->add($tournois);
            $tournois->setCategorie($this);
        }
        return $this;
    }

    public function removeTournois(Tournois $tournois): static {
        if ($this->tournois->removeElement($tournois)) {
            // set the owning side to null (unless already changed)
            if ($tournois->getCategorie() === $this) {
                $tournois->setCategorie(null);
            }
        }
        return $this;
    }
}
