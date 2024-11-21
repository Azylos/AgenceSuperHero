<?php

namespace App\Entity;

use App\Repository\SuperHeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SuperHeroRepository::class)]
class SuperHero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alterEgo;

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: 'Le niveau d\'énergie doit être compris entre {{ min }} et {{ max }}.'
    )]
    private ?int $energyLevel;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $biography = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Associations with Team entity
    #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: 'members')]
    private Collection $teams;

    #[ORM\OneToMany(targetEntity: Team::class, mappedBy: 'leader')]
    private Collection $leaderTeams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->leaderTeams = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAlterEgo(): ?string
    {
        return $this->alterEgo;
    }

    public function setAlterEgo(?string $alterEgo): static
    {
        $this->alterEgo = $alterEgo;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getEnergyLevel(): ?int
    {
        return $this->energyLevel;
    }

    public function setEnergyLevel(int $energyLevel): static
    {
        $this->energyLevel = $energyLevel;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Teams relationships
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addMember($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removeMember($this);
        }

        return $this;
    }

    public function getLeaderTeams(): Collection
    {
        return $this->leaderTeams;
    }

    public function addLeaderTeam(Team $leaderTeam): static
    {
        if (!$this->leaderTeams->contains($leaderTeam)) {
            $this->leaderTeams->add($leaderTeam);
            $leaderTeam->setLeader($this);
        }

        return $this;
    }

    public function removeLeaderTeam(Team $leaderTeam): static
    {
        if ($this->leaderTeams->removeElement($leaderTeam)) {
            if ($leaderTeam->getLeader() === $this) {
                $leaderTeam->setLeader(null);
            }
        }

        return $this;
    }
}
