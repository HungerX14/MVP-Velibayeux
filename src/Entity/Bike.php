<?php

namespace App\Entity;

use App\Repository\BikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BikeRepository::class)]
class Bike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // available | reserved
    #[ORM\Column(length: 50)]
    private string $status = 'available';

    #[ORM\ManyToOne(targetEntity: Station::class, inversedBy: 'bikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $station = null;

    public function getId(): ?int { return $this->id; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getStation(): ?Station { return $this->station; }
    public function setStation(?Station $station): static { $this->station = $station; return $this; }
}
