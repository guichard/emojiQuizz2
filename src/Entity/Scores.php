<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Scores
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'scores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $valeur;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $datePartie;

    #[ORM\Column(length: 255)]
    private ?string $historique = null;

    #[ORM\Column(length: 255)]
    private ?string $records = null;

    #[ORM\Column(length: 255)]
    private ?string $claassement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValeur(): int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDatePartie(): \DateTimeInterface
    {
        return $this->datePartie;
    }

    public function setDatePartie(\DateTimeInterface $datePartie): self
    {
        $this->datePartie = $datePartie;

        return $this;
    }

    public function getHistorique(): ?string
    {
        return $this->historique;
    }

    public function setHistorique(string $historique): static
    {
        $this->historique = $historique;

        return $this;
    }

    public function getRecords(): ?string
    {
        return $this->records;
    }

    public function setRecords(string $records): static
    {
        $this->records = $records;

        return $this;
    }

    public function getClaassement(): ?string
    {
        return $this->claassement;
    }

    public function setClaassement(string $claassement): static
    {
        $this->claassement = $claassement;

        return $this;
    }
}
