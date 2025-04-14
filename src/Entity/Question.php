<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $emoji1 = null;

    #[ORM\Column(length: 255)]
    private ?string $emoji2 = null;

    #[ORM\Column(length: 255)]
    private ?string $emoji3 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmoji1(): ?string
    {
        return $this->emoji1;
    }

    public function setEmoji1(string $emoji1): static
    {
        $this->emoji1 = $emoji1;

        return $this;
    }

    public function getEmoji2(): ?string
    {
        return $this->emoji2;
    }

    public function setEmoji2(string $emoji2): static
    {
        $this->emoji2 = $emoji2;

        return $this;
    }

    public function getEmoji3(): ?string
    {
        return $this->emoji3;
    }

    public function setEmoji3(string $emoji3): static
    {
        $this->emoji3 = $emoji3;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }
}
