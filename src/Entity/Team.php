<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, SuperHero>
     */
    #[ORM\ManyToMany(targetEntity: SuperHero::class, inversedBy: 'teams')]
    private Collection $members;

    #[ORM\ManyToOne(inversedBy: 'leaderTeams')]
    private ?SuperHero $leader = null;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'assignedTeam', fetch: 'EAGER')]
    private Collection $currentMission;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->currentMission = new ArrayCollection();
    }

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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

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

    /**
     * @return Collection<int, SuperHero>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(SuperHero $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(SuperHero $member): static
    {
        if ($this->leader === $member) {
            // Si le leader est enlevé de la liste des membres, nous devons retirer le leader aussi.
            $this->leader = null;
        }

        $this->members->removeElement($member);

        return $this;
    }

    public function getLeader(): ?SuperHero
    {
        return $this->leader;
    }

    public function setLeader(?SuperHero $leader): static
    {
        if ($leader !== null && !$this->members->contains($leader)) {
            // Si le leader n'est pas déjà un membre de l'équipe, on l'ajoute à la liste des membres.
            $this->addMember($leader);
        }

        $this->leader = $leader;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getCurrentMission(): Collection
    {
        return $this->currentMission;
    }

    public function addCurrentMission(Mission $currentMission): static
    {
        if (!$this->currentMission->contains($currentMission)) {
            $this->currentMission->add($currentMission);
            $currentMission->setAssignedTeam($this);
        }

        return $this;
    }

    public function removeCurrentMission(Mission $currentMission): static
    {
        if ($this->currentMission->removeElement($currentMission)) {
            // set the owning side to null (unless already changed)
            if ($currentMission->getAssignedTeam() === $this) {
                $currentMission->setAssignedTeam(null);
            }
        }

        return $this;
    }
}
