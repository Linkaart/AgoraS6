<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]

class Tournoi {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'tournois')]
    private ?CatTournois $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_participants = null;

    /**
     * @var Collection<int, Participant>
     */
    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'tournois')]
    private Collection $participants;

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

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static {
        $this->date = $date;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static {
        $this->dateCreation = $dateCreation;
        return $this;
    }
    public function getCategorie(): ?CatTournois
    {
        return $this->categorie;
    }
    public function setCategorie(?CatTournois $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    //! Constructeur
    public function __construct() {
        $this->dateCreation = new \DateTime('now');
        $this->participants = new ArrayCollection();
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getNbParticipants(): ?int
    {
        return $this->nb_participants;
    }
    
    public function setNbParticipants(?int $nb_participants): static
    {
        $this->nb_participants = $nb_participants;
        return $this;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }
    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }
    public function removeParticipant(Participant $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
  