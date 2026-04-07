<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['station:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['station:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['station:read'])]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups(['station:read'])]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Groups(['station:read'])]
    private int $totalSlots = 0;

    #[ORM\OneToMany(targetEntity: Bike::class, mappedBy: 'station', cascade: ['persist', 'remove'])]
    private Collection $bikes;

    public function __construct()
    {
        $this->bikes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(float $latitude): static { $this->latitude = $latitude; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(float $longitude): static { $this->longitude = $longitude; return $this; }

    public function getTotalSlots(): int { return $this->totalSlots; }
    public function setTotalSlots(int $totalSlots): static { $this->totalSlots = $totalSlots; return $this; }

    public function getBikes(): Collection { return $this->bikes; }

    #[Groups(['station:read'])]
    public function getAvailableBikes(): int
    {
        return $this->bikes->filter(fn(Bike $b) => $b->getStatus() === 'available')->count();
    }
}
