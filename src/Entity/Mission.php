<?php

namespace App\Entity;

use App\Enum\Statut;
use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(enumType: Statut::class)]
    private ?Statut $status;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt;

    #[ORM\Column(length: 255)]
    private ?string $location;

    #[ORM\Column]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Le niveau de danger doit être compris entre {{ min }} et {{ max }}.'
    )]
    private ?int $dangerLevel;

    #[ORM\ManyToOne(inversedBy: 'currentMission')]
    private ?Team $assignedTeam = null;

    /**
     * @var Collection<int, Power>
     */
    #[ORM\ManyToMany(targetEntity: Power::class, inversedBy: 'missions')]
    private Collection $powerRequire;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    public function __construct()
    {
        $this->powerRequire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?Statut
    {
        return $this->status;
    }

    public function setStatus(Statut $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDangerLevel(): ?int
    {
        return $this->dangerLevel;
    }

    public function setDangerLevel(int $dangerLevel): static
    {
        $this->dangerLevel = $dangerLevel;

        return $this;
    }

    public function getAssignedTeam(): ?Team
    {
        return $this->assignedTeam;
    }

    public function setAssignedTeam(?Team $assignedTeam): static
    {
        $this->assignedTeam = $assignedTeam;

        return $this;
    }

    /**
     * @return Collection<int, Power>
     */
    public function getPowerRequire(): Collection
    {
        return $this->powerRequire;
    }

    public function addPowerRequire(Power $powerRequire): static
    {
        if (!$this->powerRequire->contains($powerRequire)) {
            $this->powerRequire->add($powerRequire);
        }

        return $this;
    }

    public function removePowerRequire(Power $powerRequire): static
    {
        $this->powerRequire->removeElement($powerRequire);

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
