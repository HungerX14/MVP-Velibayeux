<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Bike::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bike $bike = null;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read'])]
    private ?Station $station = null;

    #[ORM\Column(length: 255)]
    #[Groups(['reservation:read'])]
    private ?string $userName = null;

    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    // pending | paid
    #[ORM\Column(length: 50)]
    #[Groups(['reservation:read'])]
    private string $paymentStatus = 'pending';

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getBike(): ?Bike { return $this->bike; }
    public function setBike(?Bike $bike): static { $this->bike = $bike; return $this; }

    public function getStation(): ?Station { return $this->station; }
    public function setStation(?Station $station): static { $this->station = $station; return $this; }

    public function getUserName(): ?string { return $this->userName; }
    public function setUserName(string $userName): static { $this->userName = $userName; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }

    public function getPaymentStatus(): string { return $this->paymentStatus; }
    public function setPaymentStatus(string $paymentStatus): static { $this->paymentStatus = $paymentStatus; return $this; }
}
